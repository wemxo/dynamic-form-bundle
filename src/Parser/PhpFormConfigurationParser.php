<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Parser;

class PhpFormConfigurationParser implements FormConfigurationParserInterface
{
    public function supportFile(string $filename): bool
    {
        return 'php' === pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function getFileExtension(): string
    {
        return 'php';
    }

    public function parseFile(string $filename): array
    {
        return include $filename;
    }
}
