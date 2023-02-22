<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dynamic_form');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('cache_pool')
                    ->info('Set the cache pool to be used to save parsed configuration.')
                    ->defaultNull()
                ->end()
                ->booleanNode('recursive')
                    ->info('Recursively search for files in configured folders (config_paths).')
                    ->defaultTrue()
                ->end()
                ->arrayNode('config_paths')
                    ->info('A list of directories paths containing the configurations files.')
                    ->defaultValue([])
                    ->prototype('scalar')
                        ->cannotBeEmpty()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
