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

class FilterTypeOptionsEvent extends Event
{
    public const NAME = 'plenta_casa.filter.type_options';

    /**
     * @param array<array<string, int|string>> $options
     * @param array<array<string, int|string>> $all
     * @param array<int>                       $count
     */
    public function __construct(
        protected array $options,
        protected ContentModel $contentModel,
        protected array $all,
        protected array $count,
    ) {
    }

    /**
     * @return array<array<string, int|string>>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array<array<string, int|string>> $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
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
     * @return array<array<string, int|string>>
     */
    public function getAll(): array
    {
        return $this->all;
    }

    /**
     * @param array<array<string, int|string>> $all
     */
    public function setAll(array $all): self
    {
        $this->all = $all;

        return $this;
    }

    /**
     * @return array<int>
     */
    public function getCount(): array
    {
        return $this->count;
    }

    /**
     * @param array<int> $count
     */
    public function setCount(array $count): self
    {
        $this->count = $count;

        return $this;
    }
}
