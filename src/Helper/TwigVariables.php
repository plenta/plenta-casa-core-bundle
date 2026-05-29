<?php

declare(strict_types=1);

/**
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Helper;

use Plenta\CasaCoreBundle\Events\AreaOptionsEvent;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class TwigVariables
{
    public function __construct(protected EventDispatcherInterface $eventDispatcher)
    {
    }

    /**
     * @return array<string>
     */
    public function getArea(ObjectModel|null $objectModel = null): array
    {
        $options = [
            'livingArea',
            'plotArea',
            'floorArea',
            'balconyArea',
            'gardenArea',
            'rooms',
            'bedrooms',
            'bathrooms',
            'floor',
            'numberOfFloors',
            'floorPosition',
            'numberOfParkingSpaces',
            'parkingSpaceType',
        ];

        $event = new AreaOptionsEvent($options, $objectModel);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getOptions();
    }
}
