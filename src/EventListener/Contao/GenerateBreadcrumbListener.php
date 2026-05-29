<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\Module;
use Plenta\CasaCoreBundle\Model\ObjectModel;

#[AsHook('generateBreadcrumb')]
class GenerateBreadcrumbListener
{
    /**
     * @param  array<int, array<string, mixed>> $items
     * @return array<int, array<string, mixed>>
     */
    public function __invoke(array $items, Module $module): array
    {
        $lastKey = array_key_last($items);

        if (null === $lastKey || !$items[$lastKey]['isActive']) {
            return $items;
        }

        $alias = basename((string) $items[$lastKey]['href']);

        $object = ObjectModel::findOneBy(['alias = ?'], [$alias]);

        if (null === $object) {
            return $items;
        }

        $items[$lastKey]['link'] = $object->title;
        $items[$lastKey]['title'] = $object->title;

        return $items;
    }
}
