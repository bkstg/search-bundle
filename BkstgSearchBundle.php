<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgSearchBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\SearchBundle;

use Bkstg\SearchBundle\Aggregation\AggregationProcessorInterface;
use Bkstg\SearchBundle\DependencyInjection\Compiler\AggregationProcessorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BkstgSearchBundle extends Bundle
{
    const TRANSLATION_DOMAIN = 'BkstgSearchBundle';

    public function build(ContainerBuilder $container): void
    {
        // Autoconfigure aggregation processors.
        $container->addCompilerPass(new AggregationProcessorPass());
        $container
            ->registerForAutoconfiguration(AggregationProcessorInterface::class)
            ->addTag('bkstg_search.aggregation_processor')
        ;
    }
}
