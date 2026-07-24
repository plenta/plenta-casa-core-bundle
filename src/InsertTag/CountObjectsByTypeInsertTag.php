<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\CoreBundle\InsertTag\Resolver\InsertTagResolverNestedResolvedInterface;
use Plenta\CasaCoreBundle\Model\ObjectModel;
use Plenta\CasaCoreBundle\Model\ObjectTypeModel;

#[AsInsertTag('casa_objects_count_by_type')]
class CountObjectsByTypeInsertTag implements InsertTagResolverNestedResolvedInterface
{
    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $typeId = $insertTag->getParameters()->get(0);

        if (null === $typeId) {
            return new InsertTagResult('0');
        }

        $ids = $this->collectTypeIds((int) $typeId);

        if (empty($ids)) {
            return new InsertTagResult('0');
        }

        $placeholders = implode(',', array_fill(0, \count($ids), '?'));

        $count = ObjectModel::countPublishedBy(
            ["tl_pcasa_object.object_type IN ($placeholders)"],
            $ids,
        );

        return new InsertTagResult((string) $count);
    }

    /**
     * @return array<int>
     */
    private function collectTypeIds(int $pid): array
    {
        $ids = [$pid];
        $children = ObjectTypeModel::findBy('pid', $pid);

        if (null === $children) {
            return $ids;
        }

        foreach ($children as $child) {
            $ids = array_merge($ids, $this->collectTypeIds((int) $child->id));
        }

        return $ids;
    }
}
