<?php

namespace Bkstg\SearchBundle\DependencyInjection\Compiler;

use Bkstg\SearchBundle\Aggregation\AggregationManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AggregationProcessorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(AggregationManager::class)) {
            return;
        }

        $definition = $container->findDefinition(AggregationManager::class);
        $tagged = $container->findTaggedServiceIds('bkstg_search.aggregation_processor');

        foreach ($tagged as $id => $tags) {
            $definition->addMethodCall('addProcessor', array(new Reference($id)));
        }
    }
}
