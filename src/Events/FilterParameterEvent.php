<?php

namespace Plenta\CasaCoreBundle\Events;

use Contao\ContentModel;
use Symfony\Contracts\EventDispatcher\Event;

class FilterParameterEvent extends Event
{
    public const NAME = 'plenta_casa.filter.parameter';

    public function __construct(
        protected ContentModel $contentModel,
        protected array $columns,
        protected array $values,
    ) {
    }

    public function getContentModel(): ContentModel
    {
        return $this->contentModel;
    }

    public function setContentModel(ContentModel $contentModel): FilterParameterEvent
    {
        $this->contentModel = $contentModel;
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): FilterParameterEvent
    {
        $this->columns = $columns;
        return $this;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function setValues(array $values): FilterParameterEvent
    {
        $this->values = $values;
        return $this;
    }
}