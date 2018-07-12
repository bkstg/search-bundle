<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle\DependencyInjection\Compiler;

use Bkstg\SearchBundle\Aggregation\AggregationManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AggregationProcessorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(AggregationManager::class)) {
            return;
        }

        $definition = $container->findDefinition(AggregationManager::class);
        $tagged = $container->findTaggedServiceIds('bkstg_search.aggregation_processor');

        foreach ($tagged as $id => $tags) {
            $definition->addMethodCall('addProcessor', [new Reference($id)]);
        }
    }
}
