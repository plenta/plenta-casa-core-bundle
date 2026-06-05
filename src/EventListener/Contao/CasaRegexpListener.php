<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao;

use Contao\Widget;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;

#[AsHook('addCustomRegexp')]
class CasaRegexpListener
{
    public function __invoke(string $regexp, $input, Widget $widget): bool
    {
        if ('casaDecimal' === $regexp) {
            if (!preg_match('/^\d+(\.\d{1,2})?$/', $input)) {
                $widget->addError('Bitte geben Sie eine gültige Zahl ein (z.B. 1500.50).');
            }

            return true;
        }

        return false;
    }
}
