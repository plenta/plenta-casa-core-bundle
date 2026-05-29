<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Events;

use Symfony\Contracts\EventDispatcher\Event;

class ObjectLabelEvent extends Event
{
    public const string NAME = 'plenta_casa.object_label';

    /**
     * @param array<string, mixed> $labelData
     */
    public function __construct(
        private readonly int $objectId,
        private array $labelData,
    ) {
    }

    public function getObjectId(): int
    {
        return $this->objectId;
    }

    /**
     * @return array<string, mixed>
     */
    public function getLabelData(): array
    {
        return $this->labelData;
    }

    /**
     * @param array<string, mixed> $labelData
     */
    public function setLabelData(array $labelData): self
    {
        $this->labelData = $labelData;

        return $this;
    }
}
