<?php

declare(strict_types=1);

/*
 * @package       Plenta Casa Core Bundle
 * @copyright     Copyright (c) 2026, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

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
                        ->performNoDeepMerging()
                    ->end()
                    ->scalarNode('locale')->end()
                    ->scalarNode('validChars')->end()
                ->end()
            ->end()
            ->integerNode('new_badge_days')
                ->defaultValue(7)
                ->end()
            ->end()
        ;
    }

    /**
     * @param array<mixed> $config
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yml');
        $container->parameters()->set('plenta_casa_core.alias_generation', $config['alias_generation']);
        $container->parameters()->set('plenta_casa.new_badge_days', $config['new_badge_days']);
    }
}
