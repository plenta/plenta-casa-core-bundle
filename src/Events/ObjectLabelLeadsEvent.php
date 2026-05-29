<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\Events;

use Symfony\Contracts\EventDispatcher\Event;

class ObjectLabelLeadsEvent extends Event
{
    public const NAME = 'plenta_casa.object_label_leads';

    private string $leads = '';

    public function __construct(
        private readonly int $objectId,
    ) {
    }

    public function getObjectId(): int
    {
        return $this->objectId;
    }

    public function getLeads(): string
    {
        return $this->leads;
    }

    public function setLeads(string $leads): void
    {
        $this->leads = $leads;
    }
}