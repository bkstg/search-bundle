<?php

namespace Bkstg\SearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BkstgSearchExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $default = $config['mapping']['default'];
        unset($config['mapping']['default']);

        // Get the metadata manager service.
        $manager = $container->get('@Bkstg\\SearchBundle\\Metadata\\MetadataManager');
        foreach ($config['mapping'] as $class => $mapping)
        {
            $metadata = ClassMetadata::createClassMetadata($class, $mapping);
            $manager->setMetadata($class, $metadata);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
