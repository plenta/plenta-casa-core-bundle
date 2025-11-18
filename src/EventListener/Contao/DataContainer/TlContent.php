<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Twig\Finder\FinderFactory;
use Contao\DataContainer;
use Contao\DC_Table;
use Plenta\CasaCoreBundle\Events\ReaderPartOptionsEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TlContent
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected FinderFactory $finderFactory,
    ) {
    }

    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_readerPartType.options')]
    public function onReaderPartTypeOptions()
    {
        $options = ['title', 'address', 'gallery', 'titleImage', 'description', 'facilities', 'area', 'price', 'condition', 'marketing'];

        $event = new ReaderPartOptionsEvent($options);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getOptions();
    }

    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_readerPartTpl.options')]
    public function onReaderPartTplOptions(DC_Table $dc)
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
}