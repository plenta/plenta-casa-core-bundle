<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener\Contao\DataContainer;

use Contao\DataContainer;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;

#[AsCallback(table: 'tl_pcasa_quarter', target: 'config.onload')]
#[AsCallback(table: 'tl_pcasa_targetgroup', target: 'config.onload')]
#[AsCallback(table: 'tl_pcasa_object_type', target: 'config.onload')]
class BackLinkListener
{
    public function __construct(
        #[Autowire('%contao.backend.route_prefix%')]
        private readonly string $backendRoutePrefix,
    ) {
    }

    public function __invoke(?DataContainer $dc = null): void
    {
        if ($dc?->table) {
            $GLOBALS['TL_DCA'][$dc->table]['config']['backlink'] = $this->backendRoutePrefix.'/plentacasa_config';
        }
    }
}
