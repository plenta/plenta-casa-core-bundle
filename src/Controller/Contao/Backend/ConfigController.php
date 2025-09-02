<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\Controller\Contao\Backend;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Contao\CoreBundle\Controller\AbstractBackendController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('%contao.backend.route_prefix%/plentacasa_config', name: self::class, defaults: ['_scope' => 'backend'])]
class ConfigController extends AbstractBackendController
{
    public function __construct(
        protected readonly RouterInterface $router,
        protected readonly RequestStack $requestStack,
        protected readonly TranslatorInterface $translator
    ) {
    }

    public function __invoke(): Response
    {
        return $this->render(
            '@Contao/Backend/plentacasa_config.html.twig',
            [
                'title' => $this->translator->trans('MOD.plenta_casa.0', [], 'contao_modules').' '.$this->translator->trans('MOD.plentacasa_config.1', [], 'contao_modules'),
                'headline' => $this->translator->trans('MOD.plenta_casa.0', [], 'contao_modules').' '.$this->translator->trans('MOD.plentacasa_config.1', [], 'contao_modules'),
            ]
        );
    }
}
