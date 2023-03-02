<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Exception;

use Exception;

class FormConfigurationNotFound extends Exception
{
    private const MESSAGE = 'Dynamic form configuration not found !';

    private string $configurationKey;

    public function __construct(string $configurationKey)
    {
        parent::__construct(self::MESSAGE, 901);

        $this->configurationKey = $configurationKey;
    }

    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
    }
}
