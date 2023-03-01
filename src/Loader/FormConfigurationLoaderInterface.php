<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Loader;

use Psr\Cache\InvalidArgumentException;
use Wemxo\DynamicFormBundle\DTO\DynamicFormConfiguration;
use Wemxo\DynamicFormBundle\Exception\FormConfigurationNotFound;

interface FormConfigurationLoaderInterface
{
    /**
     * Add configuration files path.
     */
    public function addConfigurationPath(string $configurationPath): FormConfigurationLoaderInterface;

    /**
     * If true, the loader will browse configuration folders recursively.
     */
    public function setRecursive(bool $recursive): void;

    /**
     * Get dynamic form configuration based on the given key.
     *
     * @throws FormConfigurationNotFound
     * @throws InvalidArgumentException
     */
    public function getConfiguration(string $key): DynamicFormConfiguration;
}
