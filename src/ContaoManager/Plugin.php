<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Plenta\CasaCoreBundle\PlentaCasaCoreBundle;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface/*, RoutingPluginInterface*/
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(PlentaCasaCoreBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    /*
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__.'/../Controller', 'attribute')
            ->load(__DIR__.'/../Controller')
            ;
    }
    */
}
