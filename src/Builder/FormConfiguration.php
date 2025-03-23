<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Builder;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class FormConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('form');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('subscribers')
                    ->defaultValue([])
                    ->prototype('scalar')
                        ->cannotBeEmpty()
                    ->end()
                ->end()
                ->arrayNode('transformers')
                    ->defaultValue([])
                    ->prototype('scalar')
                        ->cannotBeEmpty()
                    ->end()
                ->end()
                ->arrayNode('fields')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('transformers')
                                ->defaultValue([])
                                ->prototype('scalar')
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                            ->scalarNode('type')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifTrue(function (string $type) {
                                        return !class_exists($type);
                                    })
                                    ->thenInvalid('Invalid form type given %s.')
                                ->end()
                            ->end()
                            ->arrayNode('options')
                                ->variablePrototype()->end()
                            ->end()
                            ->arrayNode('constraints')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('class')
                                            ->isRequired()
                                            ->validate()
                                                ->ifTrue(function (string $class) {
                                                    return !class_exists($class);
                                                })
                                                ->thenInvalid('Invalid constraint class given %s.')
                                            ->end()
                                        ->end()
                                        ->arrayNode('options')
                                            ->variablePrototype()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
