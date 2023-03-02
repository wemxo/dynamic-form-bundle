<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\DTO;

class DynamicFormConfiguration
{
    private string $key;

    private array $config;

    public function __construct(string $key, array  $config)
    {
        $this->key = $key;
        $this->config = $config;
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
