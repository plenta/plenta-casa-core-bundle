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
     * @param array<Parameter>     $parameters
     * @param array<string, mixed> $filter
     */
    public function __construct(
        protected ContentModel|null $contentModel,
        protected array $parameters,
        protected array $filter,
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

    /**
     * @return array<string, mixed>
     */
    public function getFilter(): array
    {
        return $this->filter;
    }

    /**
     * @param array<string, mixed> $filter
     */
    public function setFilter(array $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
