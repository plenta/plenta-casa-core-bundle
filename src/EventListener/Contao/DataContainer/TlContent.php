<?php

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Plenta\CasaCoreBundle\Events\ReaderPartOptionsEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TlContent
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {
    }

    #[AsCallback(table: 'tl_content', target: 'fields.plenta_casa_readerParts.options')]
    public function onReaderPartsOptions()
    {
        $options = ['title', 'address', 'gallery', 'titleImage', 'description', 'facilities', 'area', 'price', 'condition', 'marketing'];

        $event = new ReaderPartOptionsEvent($options);
        $this->eventDispatcher->dispatch($event);

        return $event->getOptions();
    }
}