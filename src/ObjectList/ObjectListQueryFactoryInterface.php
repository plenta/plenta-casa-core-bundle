<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\ObjectList;

interface ObjectListQueryFactoryInterface
{
    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function create(int $contentModelId, array $filterData, int $page, string|null $sort): ObjectListQueryInterface;
}
