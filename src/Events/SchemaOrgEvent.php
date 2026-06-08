<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Events;

use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Contracts\EventDispatcher\Event;

class SchemaOrgEvent extends Event
{
    public const NAME = 'plenta_casa.schema_org';

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly ObjectModel $object,
        private array $data = [],
    ) {
    }

    public function getObject(): ObjectModel
    {
        return $this->object;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function setData(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }
}
