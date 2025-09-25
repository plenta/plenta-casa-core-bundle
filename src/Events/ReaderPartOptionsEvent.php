<?php

namespace Plenta\CasaCoreBundle\Events;

use Symfony\Contracts\EventDispatcher\Event;

class ReaderPartOptionsEvent extends Event
{
    public const NAME = 'plenta_casa.reader_part_options';

    public function __construct(
        protected array $options,
    ) {
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}