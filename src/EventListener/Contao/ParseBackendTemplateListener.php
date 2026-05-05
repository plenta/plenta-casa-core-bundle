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

#[AsHook('parseBackendTemplate')]
class ParseBackendTemplateListener
{
    public function __invoke(string $buffer, string $template): string
    {
        if ('be_main' === $template) {
            $buffer = str_replace(
                '<span class="app-title">Contao</span>',
                '<span class="app-title">Contao & Plenta Casa</span>',
                $buffer
            );
        }

        return $buffer;
    }
}
