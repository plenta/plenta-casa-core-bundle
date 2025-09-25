<?php

namespace Plenta\CasaCoreBundle\Events;

use Plenta\CasaCoreBundle\Model\ObjectModel;
use Symfony\Contracts\EventDispatcher\Event;

class AreaOptionsEvent extends Event
{
    public const NAME = 'plenta_casa.area_options';

    public function __construct(
        protected array $options,
        protected ?ObjectModel $objectModel = null,
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

    public function getObjectModel(): ?ObjectModel
    {
        return $this->objectModel;
    }

    public function setObjectModel(?ObjectModel $objectModel): void
    {
        $this->objectModel = $objectModel;
    }
}