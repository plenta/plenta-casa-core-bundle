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
use Symfony\Contracts\EventDispatcher\Event;

class ListOrderEvent extends Event
{
    public const NAME = 'plenta_casa.list.order';

    public function __construct(
        protected string $sorting,
        protected string $order,
        protected ContentModel|null $contentModel,
    ) {
    }

    public function getSorting(): string
    {
        return $this->sorting;
    }

    public function setSorting(string $sorting): self
    {
        $this->sorting = $sorting;

        return $this;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setOrder(string $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getContentModel(): ContentModel|null
    {
        return $this->contentModel;
    }

    public function setContentModel(ContentModel|null $contentModel): self
    {
        $this->contentModel = $contentModel;

        return $this;
    }
}
