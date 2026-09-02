<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\ObjectList;

interface ObjectListQueryInterface
{
    public function getContentModelId(): int;

    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function getFilterData(): array;

    public function getCurrentPaginationPage(): int|null;

    public function getSort(): string|null;

    public function getPerPage(): int|null;

    public function getMaxNumberOfItems(): int|null;

    public function getOffsetItems(): int|null;
}
