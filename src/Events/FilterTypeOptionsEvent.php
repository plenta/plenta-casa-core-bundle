<?php

namespace Plenta\CasaCoreBundle\Events;

use Contao\ContentModel;
use Symfony\Contracts\EventDispatcher\Event;

class FilterTypeOptionsEvent extends Event
{
    public const NAME = 'plenta_casa.filter.type_options';

    public function __construct(
        protected array $options,
        protected ContentModel $contentModel,
        protected array $all,
        protected array $count,
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

    public function getContentModel(): ContentModel
    {
        return $this->contentModel;
    }

    public function setContentModel(ContentModel $contentModel): FilterTypeOptionsEvent
    {
        $this->contentModel = $contentModel;
        return $this;
    }

    public function getAll(): array
    {
        return $this->all;
    }

    public function setAll(array $all): FilterTypeOptionsEvent
    {
        $this->all = $all;
        return $this;
    }

    public function getCount(): array
    {
        return $this->count;
    }

    public function setCount(array $count): FilterTypeOptionsEvent
    {
        $this->count = $count;
        return $this;
    }
}