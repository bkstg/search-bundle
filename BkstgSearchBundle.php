<?php

namespace Bkstg\SearchBundle;

use Bkstg\SearchBundle\Aggregation\AggregationProcessorInterface;
use Bkstg\SearchBundle\DependencyInjection\Compiler\AggregationProcessorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BkstgSearchBundle extends Bundle
{
    const TRANSLATION_DOMAIN = 'BkstgSearchBundle';

    public function build(ContainerBuilder $container)
    {
        // Autoconfigure aggregation processors.
        $container->addCompilerPass(new AggregationProcessorPass());
        $container
            ->registerForAutoconfiguration(AggregationProcessorInterface::class)
            ->addTag('bkstg_search.aggregation_processor')
        ;
    }
}
