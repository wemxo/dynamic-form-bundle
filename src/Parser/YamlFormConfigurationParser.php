<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Parser;

use Symfony\Component\Yaml\Yaml;

class YamlFormConfigurationParser implements FormConfigurationParserInterface
{
    public function supportFile(string $filename): bool
    {
        return 'yaml' === pathinfo($filename, PATHINFO_EXTENSION);
    }

    public function getFileExtension(): string
    {
        return 'yaml';
    }

    public function parseFile(string $filename): array
    {
        return Yaml::parseFile($filename);
    }
}
