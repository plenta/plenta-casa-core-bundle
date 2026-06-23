<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\Input;
use Plenta\CasaCoreBundle\Model\ObjectModel;

use Contao\CoreBundle\InsertTag\Exception\InvalidInsertTagException;
use Contao\CoreBundle\InsertTag\Resolver\InsertTagResolverNestedResolvedInterface;

#[AsInsertTag('plentacasa')]
class InsertTagListener implements InsertTagResolverNestedResolvedInterface
{
    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $type = $insertTag->getParameters()->get(0);
        $field = $insertTag->getParameters()->get(1);

        if ('object' !== $type || null === $field) {
            return new InsertTagResult('', OutputType::text);
        }

        $alias = Input::get('auto_item');

        if (!$alias) {
            return new InsertTagResult('', OutputType::text);
        }

        $object = ObjectModel::findPublishedByIdOrAlias($alias);

        if (null === $object) {
            return new InsertTagResult('', OutputType::text);
        }

        $value = match ($field) {
            'id' => (string) $object->id,
            'alias' => $object->alias,
            'address' => $object->getAddress(),
            'name' => $object->getName(),
            'title' => $object->getName(),
            default => '',
        };

        return new InsertTagResult($value, OutputType::text);
    }
}
