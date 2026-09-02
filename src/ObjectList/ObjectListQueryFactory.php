<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\ObjectList;

class ObjectListQueryFactory
{
    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function create(int $contentModelId, array $filterData, int|null $currentPaginationPage = null, int|null $maxNumberOfItem = null, int|null $offsetItems = null, string|null $sort = null, int|null $perPage = null): ObjectListQueryInterface
    {
        return new ObjectListQuery(
            $contentModelId,
            $filterData,
            $currentPaginationPage,
            $maxNumberOfItem,
            $offsetItems,
            $sort,
            $perPage,
        );
    }
}
