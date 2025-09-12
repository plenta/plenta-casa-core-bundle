<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Controller\Contao\Backend;

use Contao\CoreBundle\Controller\AbstractBackendController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('%contao.backend.route_prefix%/plentacasa_import', name: self::class, defaults: ['_scope' => 'backend'])]
class ImportController extends AbstractBackendController
{
    public function __construct(
        protected readonly RouterInterface $router,
        protected readonly RequestStack $requestStack,
        protected readonly TranslatorInterface $translator
    ) {
    }

    public function __invoke(): Response
    {
        $GLOBALS['TL_CSS']['plentacasacore_config'] = 'bundles/plentacasacore/config.css';

        $titleLabel = $this->translator->trans('MOD.plenta_casa.0', [], 'contao_modules').' '.$this->translator->trans('MOD.plentacasa_import.1', [], 'contao_modules');

        $message = $this->translator->trans('PLENTA.CASA.messages.addons', [], 'contao_default');

        return $this->render(
            '@Contao/Backend/plentacasa_import.html.twig',
            [
                'message' => $message,
                'title' => $titleLabel,
                'headline' => $titleLabel,
            ]
        );
    }
}
