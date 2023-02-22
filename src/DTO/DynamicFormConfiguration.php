<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\DTO;

class DynamicFormConfiguration
{
    public function __construct(
        private readonly string $key,
        private readonly array  $config
    ) {
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}
