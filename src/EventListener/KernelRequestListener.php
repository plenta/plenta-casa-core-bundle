<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class KernelRequestListener
{
    public function __construct(private readonly ContaoFramework $contaoFramework)
    {
    }

    #[AsEventListener('kernel.request')]
    public function onKernelRequest(RequestEvent $event): void
    {
        if ('ux_live_component' === $event->getRequest()->attributes->get('_route')) {
            $this->contaoFramework->initialize();
        }
    }
}
