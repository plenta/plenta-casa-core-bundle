<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Helper;

use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Symfony\Component\HttpFoundation\RequestStack;

class ObjectTypes
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function hasObjects(int $id): bool
    {
        if (ObjectModel::findPublishedBy(['object_type = ?'], [$id])) {
            return true;
        }

        $children = ObjectTypeModel::findBy('pid', $id);

        if ($children) {
            foreach ($children as $child) {
                if ($this->hasObjects($child->id)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function countObjects(int $id): int
    {
        $count = ObjectModel::countPublishedBy(['object_type = ?'], [$id]);

        $children = ObjectTypeModel::findBy('pid', $id);

        if ($children) {
            foreach ($children as $child) {
                $count += $this->countObjects($child->id);
            }
        }

        return $count;
    }

    /**
     * @return array<int, string>
     */
    public function getTypes(int $pid = 0, string $prefix = '', bool $showChildrenOnlyWhenChecked = false): array
    {
        $return = [];

        $types = ObjectTypeModel::findBy('pid', $pid, ['order' => 'sorting ASC']);

        if ($types) {
            foreach ($types as $type) {
                $return[$type->id] = $prefix.($prefix ? ' ' : '').$type->title;

                if ($showChildrenOnlyWhenChecked) {
                    $request = $this->requestStack->getCurrentRequest();

                    if ($request->query->has('filter')) {
                        $filter = $request->query->all('filter');

                        if (!\in_array($type->id, $filter['objectType'] ?? [], true)) {
                            continue;
                        }
                    }
                }
                $return += $this->getTypes($type->id, $prefix.'-');
            }
        }

        return $return;
    }
}
