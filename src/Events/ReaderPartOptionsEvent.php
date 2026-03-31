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

class ReaderPartOptionsEvent extends Event
{
    public const NAME = 'plenta_casa.reader_part_options';

    /**
     * @param array<string> $options
     */
    public function __construct(
        protected array $options,
    ) {
    }

    /**
     * @return array<string>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array<string> $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
