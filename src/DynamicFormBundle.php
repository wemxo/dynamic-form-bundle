<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Wemxo\DynamicFormBundle\DependencyInjection\CompilerPass\DynamicFormCompilerPass;

class DynamicFormBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DynamicFormCompilerPass());
    }
}
