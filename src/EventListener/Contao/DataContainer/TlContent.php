<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Twig\Finder\FinderFactory;
use Contao\DataContainer;
use Plenta\CasaCoreBundle\Events\ReaderPartOptionsEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TlContent
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected FinderFactory $finderFactory,
    ) {
    }

    /**
     * @return array<string>
     */
    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_readerPartType.options')]
    public function onReaderPartTypeOptions(): array
    {
        $options = ['title', 'address', 'gallery', 'titleImage', 'description', 'facilities', 'area', 'price', 'condition', 'marketing'];

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

    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_sorting.options')]
    public function onListSortingOptions(): array
    {
        return $GLOBALS['PLENTA']['CASA']['sorting_type'];
    }
}
