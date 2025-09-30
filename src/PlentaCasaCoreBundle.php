<?php

declare(strict_types=1);

namespace Plenta\CasaCoreBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class PlentaCasaCoreBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('alias_generation')
                    ->children()
                        ->arrayNode('parts')
                            ->scalarPrototype()->end()
                        ->end()
                        ->scalarNode('locale')->end()
                        ->scalarNode('validChars')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(
        array                 $config,
        ContainerConfigurator $container,
        ContainerBuilder      $builder,
    ): void
    {
        $container->import('../config/services.yml');
        $container->parameters()->set('plenta_casa_core.alias_generation', $config['alias_generation']);
    }
}
