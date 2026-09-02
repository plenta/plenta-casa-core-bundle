<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\ObjectList;

readonly class ObjectListQuery implements ObjectListQueryInterface
{
    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function __construct(
        protected int $contentModelId,
        protected array $filterData,
        protected int|null $currentPaginationPage,
        protected int|null $maxNumberOfItems = null,
        protected int|null $offsetItems = null,
        protected string|null $sort = null,
        protected int|null $perPage = null,
    ) {
    }

    public function getContentModelId(): int
    {
        return $this->contentModelId;
    }

    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function getFilterData(): array
    {
        return $this->filterData;
    }

    public function getCurrentPaginationPage(): int|null
    {
        return $this->currentPaginationPage;
    }

    public function getSort(): string|null
    {
        return $this->sort;
    }

    public function getPerPage(): int|null
    {
        return $this->perPage;
    }

    public function getMaxNumberOfItems(): int|null
    {
        return $this->maxNumberOfItems;
    }

    public function getOffsetItems(): int|null
    {
        return $this->offsetItems;
    }
}
