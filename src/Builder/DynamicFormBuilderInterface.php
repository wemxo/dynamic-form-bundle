<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Builder;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;
use Wemxo\DynamicFormBundle\Exception\FormConfigurationNotFound;

interface DynamicFormBuilderInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws FormConfigurationNotFound
     */
    public function build(string $key, FormBuilderInterface $builder): void;
}
