<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Events;

use Contao\ContentModel;
use Plenta\CasaCoreBundle\Filter\Parameter;
use Symfony\Contracts\EventDispatcher\Event;

class FilterParameterEvent extends Event
{
    public const NAME = 'plenta_casa.filter.parameter';

    /**
     * @param array<Parameter> $parameters
     */
    public function __construct(
        protected ContentModel $contentModel,
        protected array $parameters,
    ) {
    }

    public function getContentModel(): ContentModel
    {
        return $this->contentModel;
    }

    public function setContentModel(ContentModel $contentModel): self
    {
        $this->contentModel = $contentModel;

        return $this;
    }

    /**
     * @return array<Parameter>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<Parameter> $parameters
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }
}
