<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\DependencyInjection\CompilerPass;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Wemxo\DynamicFormBundle\DependencyInjection\Configuration;
use Wemxo\DynamicFormBundle\Loader\FormConfigurationLoader;

class DynamicFormCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(FormConfigurationLoader::class)) {
            return;
        }

        $configurationLoaderDefinition = $container->getDefinition(FormConfigurationLoader::class);
        $dynamicFormBundleConfig = (new Processor())
            ->processConfiguration(
                new Configuration(),
                $container->getExtensionConfig('dynamic_form')
            )
        ;

        if (empty($dynamicFormBundleConfig['cache_pool'])) {
            return;
        }

        if (!$container->has($dynamicFormBundleConfig['cache_pool'])) {
            throw new InvalidConfigurationException(
                sprintf('Invalid cache pool name given %s.', $dynamicFormBundleConfig['cache_pool'])
            );
        }

        $configurationLoaderDefinition->addMethodCall('setCache', [new Reference($dynamicFormBundleConfig['cache_pool'])]);
    }
}
