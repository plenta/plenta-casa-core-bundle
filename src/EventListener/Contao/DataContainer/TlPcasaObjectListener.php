<?php

declare(strict_types=1);

/**
 * @package       Customer
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
use Plenta\CasaCoreBundle\Helper\AliasGenerator;
use Plenta\CasaCoreBundle\Helper\ObjectTypes;
use Plenta\CasaCoreBundle\Model\LeadModel;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Symfony\Component\Asset\Packages;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment as TwigEnvironment;

class TlPcasaObjectListener
{
    public function __construct(
        protected AliasGenerator $aliasGenerator,
        protected Packages $packages,
        protected readonly TranslatorInterface $translator,
        protected readonly TwigEnvironment $twig,
        private readonly ObjectTypes $objectTypes,
        private readonly ContentUrlGenerator $contentUrlGenerator,
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

        return $this->twig->render('@Contao/Backend/plentacasa_listing.html.twig', [
            'property' => $property,
            'image' => $this->getImage($property),
            'leads' => $this->getLeads($property['id']),
        ]);
    }

    /**
     * @return array<int, string>
     */
    #[AsCallback(table: 'tl_pcasa_object', target: 'fields.object_type.options')]
    public function typeOptionsCallback(DataContainer $dc): array
    {
        return $this->objectTypes->getTypes();
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
                        ['parameters' => '/'.$slug]
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
    public function getImage(array $property): ?string
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

    public function getLeads(int $object): string
    {
        $leads = LeadModel::countBy('object', $object) ?? 0;

        return $this->translator->trans('PLENTA.CASA.lead_count', ['%count%' => $leads], 'plenta_casa_backend');
    }
}
