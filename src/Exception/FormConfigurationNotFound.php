<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Exception;

use Exception;

class FormConfigurationNotFound extends Exception
{
    private const MESSAGE = 'Dynamic form configuration not found !';
    public function __construct(private readonly string $configurationKey)
    {
        parent::__construct(self::MESSAGE, 901, null);
    }

    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
    }
}
