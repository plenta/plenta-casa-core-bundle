<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Twig;

use Plenta\CasaCoreBundle\Model\ObjectTypeModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Attribute\AsTwigFilter;

class PlentaCasaExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

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

    protected function getTitle(ObjectTypeModel $type): string
    {
        $return = '';

        if ($type->pid) {
            $return .= $this->getTitle(ObjectTypeModel::findById($type->pid)).' > ';
        }

        return $return.$type->title;
    }

    #[AsTwigFilter('casaFormatNumber')]
    public function casaFormatNumber(int|float|string $value, int $decimals = 2): string
    {
        $locale = $this->requestStack->getCurrentRequest()?->getLocale() ?? 'de_DE';

        $formatter = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $decimals);

        return $formatter->format((float) $value);
    }
}
