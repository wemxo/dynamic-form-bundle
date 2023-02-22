<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Wemxo\DynamicFormBundle\Loader\FormConfigurationLoader;

class DynamicFormExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );
        $loader->load('services.yaml');
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $configurationLoaderDefinition = $container->getDefinition(FormConfigurationLoader::class);
        $configurationLoaderDefinition->addMethodCall('setRecursive', [$config['recursive']]);
        foreach ($config['config_paths'] as $configPath) {
            $configurationLoaderDefinition->addMethodCall('addConfigurationPath', [$configPath]);
        }
    }
}
