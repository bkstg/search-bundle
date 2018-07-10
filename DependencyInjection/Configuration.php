<?php

namespace Bkstg\SearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bkstg_search');

        $rootNode
            ->children()
                ->arrayNode('mapping')
                    ->children()
                        ->arrayNode('default')
                            ->arrayPrototype()
                                ->children()
                                    ->floatNode('boost')->end()
                                ->end()
                            ->end()
                        ->addDefaultsIfNotSet()
                        ->end()
                    ->end()
                    ->arrayPrototype()
                        ->arrayPrototype()
                            ->children()
                                ->floatNode('boost')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
