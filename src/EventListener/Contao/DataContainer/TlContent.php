<?php

declare(strict_types=1);

/**
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\ContentModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Twig\Finder\FinderFactory;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Plenta\CasaCoreBundle\Events\ReaderPartOptionsEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TlContent
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected FinderFactory $finderFactory,
        protected readonly RequestStack $requestStack,
        private readonly Connection $connection,
    ) {
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_readerPartType.options')]
    public function onReaderPartTypeOptions(): array
    {
        $options = [
            'title',
            'address',
            'unit_id',
            'titleImage',
            'gallery',
            'floorplan',
            'description',
            'location',
            'equipment',
            'other',
            'facilities',
            'area',
            'price',
            'courtage',
            'condition',
            'marketing',
            'enclosure',
            'status',
            'mainInfo',
        ];

        $event = new ReaderPartOptionsEvent($options);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getOptions();
    }

    /**
     * @return array<string, string>
     */
    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_readerPartTpl.options')]
    public function onReaderPartTplOptions(DataContainer $dc): array
    {
        $field = $dc->field;
        $source = $dc->activeRecord->{str_replace('readerPartTpl', 'readerPartType', $field)};

        if (empty($source)) {
            return [];
        }

        return $this->finderFactory
            ->create()
            ->identifier('casa/reader/parts/'.$source)
            ->extension('html.twig')
            ->withVariants()
            ->asTemplateOptions()
        ;
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_sorting.options')]
    public function onListSortingOptions(): array
    {
        return $GLOBALS['PLENTA']['CASA']['sorting_type'];
    }

    #[AsCallback('tl_content', 'config.onload')]
    public function onLoadCallback(DataContainer|null $dc = null): void
    {
        if (null === $dc || !$dc->id || 'edit' !== $this->requestStack->getCurrentRequest()->query->get('act')) {
            return;
        }

        $element = ContentModel::findById($dc->id);

        if (null === $element || 'plenta_casa_listing' !== $element->type) {
            return;
        }

        $GLOBALS['TL_DCA']['tl_content']['fields']['jumpTo']['eval']['mandatory'] = true;
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_preFilterLocation.options')]
    public function onLocationOptions(DataContainer $dc): array
    {
        return $this->connection->createQueryBuilder()
            ->select('city')
            ->from('tl_pcasa_object')
            ->distinct()
            ->fetchFirstColumn()
        ;
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_preFilterDistrict.options')]
    public function onDistrictOptions(DataContainer $dc): array
    {
        return $this->connection->createQueryBuilder()
            ->select('district')
            ->from('tl_pcasa_object')
            ->distinct()
            ->fetchFirstColumn()
        ;
    }
}
