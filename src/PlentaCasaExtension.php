<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle;

use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Plenta\CasaCoreBundle\Model\TypeModel;
use Twig\Attribute\AsTwigFilter;

class PlentaCasaExtension
{
    #[AsTwigFilter('casaTypeName')]
    public function casaTypeName(int $value): string
    {
        return ($value && ($type = TypeModel::findByPk($value)))
            ? ($type->title ?? '-')
            : '-';
    }

    #[AsTwigFilter('casaObjectTypeName')]
    public function casaObjectTypeName(int $value): string
    {
        return ($value && ($type = ObjectTypeModel::findByPk($value)))
            ? ($type->title ?? '-')
            : '-';
    }
}
