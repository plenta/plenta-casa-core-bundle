<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Controller\Contao\Backend;

use Contao\CoreBundle\Controller\AbstractBackendController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('%contao.backend.route_prefix%/plentacasa_addons', name: self::class, defaults: ['_scope' => 'backend'])]
class AddonsController extends AbstractBackendController
{
    public function __construct(
        protected readonly RouterInterface $router,
        protected readonly RequestStack $requestStack,
        protected readonly TranslatorInterface $translator,
    ) {
    }

    public function __invoke(): Response
    {
        $GLOBALS['TL_CSS']['plentacasacore_config'] = 'bundles/plentacasacore/config.css';

        $titleLabel = $this->translator->trans(
            'MOD.plenta_casa.0',
            [],
            'contao_modules').' '.$this->translator->trans('MOD.plentacasa_addons.1', [], 'contao_modules'
        );

        return $this->render('@Contao/Backend/plentacasa_addons.html.twig', [
            'message' => $this->translator->trans('PLENTA.CASA.messages.addons', [], 'contao_default'),
            'title' => $titleLabel,
            'headline' => $titleLabel,
        ]);
    }
}
