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
        $GLOBALS['TL_CSS']['plentacasacore_config'] = 'bundles/plentacasacore/config.css';

        $titleLabel = $this->translator->trans('MOD.plenta_casa.0', [], 'contao_modules').' '.$this->translator->trans('MOD.plentacasa_config.1', [], 'contao_modules');

        return $this->render(
            '@Contao/Backend/plentacasa_config.html.twig',
            [
                'title' => $titleLabel,
                'headline' => $titleLabel,
                'tables' => $this->getTables(),
            ]
        );
    }

    protected function getTables(): array
    {
        $tables = [];

        foreach ($GLOBALS['PLENTA']['CASA']['config'] as $table) {
            $tables[] = [
                'name' => $table,
                'label' => $this->translator->trans('MOD.plentacasa_'.$table.'.0', [], 'contao_modules') ?: $table,
                'description' => $this->translator->trans('MOD.plentacasa_'.$table.'.1', [], 'contao_modules') ?: $table,
                'href' => '?do=plentacasa_config&table='.$table,
            ];
        }

        return $tables;
    }
}
