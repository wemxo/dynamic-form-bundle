<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wemxo\DynamicFormBundle\DataTransformer\DynamicDataTransformer;
use Wemxo\DynamicFormBundle\DependencyInjection\CompilerPass\AutoTagCompilerPass;
use Wemxo\DynamicFormBundle\DependencyInjection\CompilerPass\DynamicFormCompilerPass;
use Wemxo\DynamicFormBundle\EventSubscriber\DynamicFormEventSubscriber;
use Wemxo\DynamicFormBundle\Parser\FormConfigurationParserInterface;

class DynamicFormBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container
            ->registerForAutoconfiguration(DynamicDataTransformer::class)
            ->addTag('wemxo.dynamic_form.data_transformer')
        ;
        $container
            ->registerForAutoconfiguration(DynamicFormEventSubscriber::class)
            ->addTag('wemxo.dynamic_form.event_subscriber')
        ;
        $container
            ->registerForAutoconfiguration(FormConfigurationParserInterface::class)
            ->addTag('wemxo.dynamic_form.form_configuration_parser')
        ;
        $container->addCompilerPass(new DynamicFormCompilerPass());
    }
}
