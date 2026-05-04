<?php

declare(strict_types=1);

/**
 * @package       Customer
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

    public function getTypes(int $pid = 0, int $depth = 0): array
    {
        $return = [];
        $types = ObjectTypeModel::findBy('pid', $pid, ['order' => 'title ASC']);

        if (!$types) {
            return $return;
        }

        foreach ($types as $type) {
            $indent = $depth > 0 ? str_repeat('　', $depth - 1).'└ ' : '';
            $return[$type->id] = $indent.$type->title;
            $return += $this->getTypes($type->id, $depth + 1);
        }

        return $return;
    }
}
