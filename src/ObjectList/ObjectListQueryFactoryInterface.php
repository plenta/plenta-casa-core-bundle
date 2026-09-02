<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\ObjectList;

interface ObjectListQueryFactoryInterface
{
    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function create(int $contentModelId, array $filterData, int $page, string|null $sort): ObjectListQueryInterface;
}
