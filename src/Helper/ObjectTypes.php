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

class ObjectTypes
{
    /**
     * @param array<string> $columns
     * @param array<mixed>  $values
     */
    public function hasObjects(int $id, array $columns = [], array $values = []): bool
    {
        if (ObjectModel::findPublishedBy(array_merge($columns, ['object_type = ?']), array_merge($values, [$id]))) {
            return true;
        }

        $children = ObjectTypeModel::findBy('pid', $id);

        if ($children) {
            foreach ($children as $child) {
                if ($this->hasObjects($child->id, $columns, $values)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array<string> $columns
     * @param array<mixed>  $values
     */
    public function countObjects(int $id, array $columns = [], array $values = []): int
    {
        $count = ObjectModel::countPublishedBy(array_merge($columns, ['object_type = ?']), array_merge($values, [$id]));

        $children = ObjectTypeModel::findBy('pid', $id);

        if ($children) {
            foreach ($children as $child) {
                $count += $this->countObjects($child->id, $columns, $values);
            }
        }

        return $count;
    }

    /**
     * @param  array<string>      $checked
     * @return array<int, string>
     */
    public function getTypes(int $pid = 0, string $prefix = '', bool $showChildrenOnlyWhenChecked = false, array $checked = []): array
    {
        $types = ObjectTypeModel::findBy('pid', $pid, ['order' => 'sorting ASC']);

        $return = [];

        if ($types) {
            foreach ($types as $type) {
                $return[$type->id] = $prefix.($prefix ? ' ' : '').$type->title;

                if ($showChildrenOnlyWhenChecked) {
                    if (!\in_array((string) $type->id, $checked, true)) {
                        continue;
                    }
                }

                $return += $this->getTypes($type->id, $prefix.'-');
            }
        }

        return $return;
    }
}
