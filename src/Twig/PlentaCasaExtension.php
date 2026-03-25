<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Twig;

use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Twig\Attribute\AsTwigFilter;

class PlentaCasaExtension
{
    #[AsTwigFilter('casaObjectTypeName')]
    public function casaObjectTypeName(int $value): string
    {
        $type = ObjectTypeModel::findById($value);

        if ($type) {
            return $this->getTitle($type);
        }

        return '-';
    }

    #[AsTwigFilter('casaObjectType')]
    public function casaObjectType(int $value): ObjectTypeModel
    {
        return ObjectTypeModel::findById($value);
    }

    protected function getTitle(ObjectTypeModel $type)
    {
        $return = '';

        if ($type->pid) {
            $return .= $this->getTitle(ObjectTypeModel::findById($type->pid)).' > ';
        }

        $return .= $type->title;

        return $return;
    }
}
