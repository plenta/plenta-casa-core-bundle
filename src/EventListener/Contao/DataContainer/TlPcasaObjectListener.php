<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\ContentModel;
use Contao\CoreBundle\DataContainer\DataContainerOperation;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Routing\ContentUrlGenerator;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\FilesModel;
use Contao\StringUtil;
use Plenta\CasaCoreBundle\Events\ObjectLabelEvent;
use Plenta\CasaCoreBundle\Helper\AliasGenerator;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment as TwigEnvironment;

class TlPcasaObjectListener
{
    public function __construct(
        protected AliasGenerator $aliasGenerator,
        protected Packages $packages,
        protected readonly TranslatorInterface $translator,
        protected readonly TwigEnvironment $twig,
        private readonly ContentUrlGenerator $contentUrlGenerator,
        private readonly RequestStack $requestStack,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @param array<string, mixed> $row
     * @param array<string>        $labels
     */
    #[AsCallback(table: 'tl_pcasa_object', target: 'list.label.label')]
    public function objectLabelCallback(array $row, string $label, DataContainer $dc, array $labels): string
    {
        $property = $row;

        $this->getImage($property);

        $labelData = [
            'property' => $property,
            'image' => $this->getImage($property),
            'price' => $this->getObjectPrice($property),
            'size' => $this->getObjectSize($property),
        ];

        $labelEvent = new ObjectLabelEvent((int) $property['id'], $labelData);
        $this->eventDispatcher->dispatch($labelEvent, ObjectLabelEvent::NAME);

        return $this->twig->render('@Contao/Backend/plentacasa_listing.html.twig', $labelEvent->getLabelData());
    }

    /**
     * @param array<string, mixed> $property
     */
    public function getObjectPrice(array $property): string
    {
        $value = match ($property['marketing_type']) {
            'RENT' => $property['totalRent'] ?? $property['baseRent'] ?? null,
            'BUY' => $property['price'] ?? null,
            default => null,
        };

        return $value ? $this->formatNumber($value) : '-';
    }

    /**
     * @param array<string, mixed> $property
     */
    public function getObjectSize(array $property): string
    {
        $fields = ['floorArea', 'plotArea', 'livingArea'];

        if (\in_array($property['marketing_type'], ['RENT', 'BUY'], true)) {
            foreach ($fields as $field) {
                if (0 !== (int) $property[$field]) {
                    return $this->formatNumber($property[$field]);
                }
            }
        }

        return '-';
    }

    public function formatNumber(float|int|string $value): string
    {
        $locale = $this->requestStack->getCurrentRequest()?->getLocale() ?? 'de';

        $formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);

        return $formatter->format((float) $value);
    }

    /**
     * @return array<int, string>
     */
    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.object_type.options')]
    public function typeOptionsCallback(DataContainer $dc): array
    {
        $return = [];
        $types = ObjectTypeModel::findBy('pid', 0, ['order' => 'title ASC']);

        if (!$types) {
            return $return;
        }

        foreach ($types as $type) {
            $return[$type->id] = $type->title;
            $return += $this->getObjectTypes($type->id, 1);
        }

        return $return;
    }

    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.alias.save')]
    public function aliasSaveCallback(string $varValue, DC_Table $dc): string
    {
        if (!empty($varValue)) {
            if (preg_match('/^[1-9]\d*$/', $varValue)) {
                throw new \Exception(\sprintf($GLOBALS['TL_LANG']['ERR']['aliasNumeric'], $varValue));
            }
            if (null !== ObjectModel::findBy(['tl_pcasa_object.id != ?', 'alias = ?'], [$dc->id, $varValue])) {
                throw new \Exception(\sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            return $varValue;
        }

        return $this->aliasGenerator->generateAlias($dc->getActiveRecord());
    }

    #[AsCallback(table: 'tl_pcasa_object', target: 'list.operations.preview.button')]
    public function onPreviewCallback(DataContainerOperation $operation): void
    {
        $record = $operation->getRecord();

        if (0 === (int) $record['published']) {
            $operation->setUrl('');
            $operation['icon'] = null;

            return;
        }

        $url = '';
        $slug = !empty($record['alias']) ? $record['alias'] : $record['id'];

        if ($lists = ContentModel::findByType('plenta_casa_listing')) {
            foreach ($lists as $list) {
                try {
                    $url = $this->contentUrlGenerator->generate(
                        $list->getRelated('jumpTo'),
                        ['parameters' => '/'.$slug],
                    );
                } catch (\Throwable) {
                    $url = null;
                }

                if ($url) {
                    break;
                }
            }
        }

        $operation->setUrl($url);
        $operation['icon'] = $this->packages->getUrl('plentacasacore/images/link.svg', 'plentacasacore');
    }

    public function getSerpUrl(ObjectModel $object): string
    {
        $strSuffix = '/';

        return \sprintf(preg_replace('/%(?!s)/', '%%', $strSuffix), $object->alias ?: $object->id);
    }

    #[AsCallback(table: 'tl_pcasa_object', target: 'config.onsubmit')]
    public function onSubmit(DataContainer $dc): void
    {
        if (!$dc->activeRecord->createdAt) {
            $object = ObjectModel::findById($dc->id);
            $object->createdAt = time();
            $object->save();
        }
    }

    /**
     * @param array<string, mixed> $property
     */
    public function getImage(array $property): string|null
    {
        if (null !== $property['singleSRC']) {
            $objFile = FilesModel::findByUuid(StringUtil::binToUuid($property['singleSRC']));

            return $objFile->path;
        }

        $placeholder = ObjectTypeModel::findById($property['object_type']);

        if (null !== $placeholder && null !== $placeholder->singleSRC) {
            $objFile = FilesModel::findByUuid(StringUtil::binToUuid($placeholder->singleSRC));

            return $objFile->path;
        }

        return null;
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.facilities.options')]
    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.bathroomFacilities.options')]
    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.flooring.options')]
    public function sortOptionsCallback(DataContainer $dc): array
    {
        $field = $dc->field;
        $options = $GLOBALS['TL_DCA']['tl_pcasa_object']['fields'][$field]['options'] ?? [];
        $labels = $GLOBALS['TL_LANG']['tl_pcasa_object'][$field.'-options'] ?? [];

        usort($options, static fn (string $a, string $b): int => strnatcasecmp($labels[$a] ?? $a, $labels[$b] ?? $b));

        return array_combine($options, array_map(
            static fn (string $key): string => $labels[$key] ?? $key,
            $options,
        ));
    }

    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.heatingType.options')]
    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.primaryEnergySource.options')]
    public function energyOptionsCallback(DataContainer $dc): array
    {
        $field = $dc->field;
        $options = $GLOBALS['TL_DCA']['tl_pcasa_object']['fields'][$field]['options'] ?? [];
        $locale = $this->requestStack->getCurrentRequest()?->getLocale() ?? 'de';

        $labels = [];
        foreach ($options as $key) {
            $labels[$key] = $this->translator->trans('PLENTA.CASA.'.$field.'.'.$key, [], 'contao_default');
        }

        $collator = new \Collator($locale);

        usort($options, static fn (string $a, string $b): int => $collator->compare($labels[$a] ?? $a, $labels[$b] ?? $b));

        return array_combine($options, array_map(
            static fn (string $key): string => $labels[$key] ?? $key,
            $options,
        ));
    }

    /**
     * @return array<int, string>
     */
    private function getObjectTypes(int $pid, int $depth): array
    {
        $return = [];
        $types = ObjectTypeModel::findBy('pid', $pid, ['order' => 'title ASC']);

        if (!$types) {
            return $return;
        }

        foreach ($types as $type) {
            $indent = str_repeat('　', $depth - 1).'└ ';
            $return[$type->id] = $indent.$type->title;
            $return += $this->getObjectTypes($type->id, $depth + 1);
        }

        return $return;
    }
}
