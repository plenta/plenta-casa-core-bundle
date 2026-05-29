<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Events;

use Symfony\Contracts\EventDispatcher\Event;

class DashboardStatsEvent extends Event
{
    public const NAME = 'plenta_casa.dashboard_stats';

    public function __construct(
        private array $data = [],
    ) {
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }
}