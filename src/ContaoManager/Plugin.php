<?php

declare(strict_types=1);

/**
 * @package       Customer
 * @copyright     Copyright (c) 2025, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Plenta\CasaCoreBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Plenta\CasaCoreBundle\PlentaCasaCoreBundle;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface, ConfigPluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(PlentaCasaCoreBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(__DIR__.'/../../config/config.yml');
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__.'/../Controller', 'attribute')
            ->load(__DIR__.'/../Controller')
        ;
    }
}
