<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Helper;

use Contao\ContentModel;
use Plenta\CasaCoreBundle\Events\ListOrderEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class Sorting
{
    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    public function getListSorting(string|null $sorting, ContentModel $model): string
    {
        $order = match ($sorting) {
            'order_headline_asc' => 'tl_pcasa_object.title',
            'order_headline_desc' => 'tl_pcasa_object.title DESC',
            'order_random' => 'RAND()',
            'order_date_asc' => 'tl_pcasa_object.tstamp',
            'order_date_desc' => 'tl_pcasa_object.tstamp DESC',
            default => '',
        };

        $event = new ListOrderEvent($sorting, $order, $model);
        $this->eventDispatcher->dispatch($event, $event::NAME);

        return $event->getOrder();
    }
}
