<?php

namespace Plenta\CasaCoreBundle\Events;

use Contao\ContentModel;
use Plenta\CasaCoreBundle\Filter\Parameter;
use Symfony\Contracts\EventDispatcher\Event;

class FilterParameterEvent extends Event
{
    public const NAME = 'plenta_casa.filter.parameter';

    public function __construct(
        protected ContentModel $contentModel,
        protected array $parameters,
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

    /**
     * @return Parameter[]
    */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): FilterParameterEvent
    {
        $this->parameters = $parameters;
        return $this;
    }
}