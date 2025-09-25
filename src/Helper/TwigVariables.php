<?php

namespace Plenta\CasaCoreBundle\Helper;

use Plenta\CasaCoreBundle\Events\AreaOptionsEvent;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TwigVariables
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
    ) {

    }

    public function getArea(?ObjectModel $objectModel = null): array
    {
        $options = ['livingArea', 'plotArea', 'rooms', 'bedrooms', 'bathrooms', 'floorArea', 'balconyArea', 'gardenArea', 'floor', 'numberOfFloors', 'floorPosition', 'numberOfParkingSpaces', 'parkingSpaceType'];

        $event = new AreaOptionsEvent($options, $objectModel);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getOptions();
    }
}