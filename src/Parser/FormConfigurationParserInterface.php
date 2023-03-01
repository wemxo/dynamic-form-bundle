<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Parser;

interface FormConfigurationParserInterface
{
    /**
     * Check if the given file was supported.
     */
    public function supportFile(string $filename): bool;

    /**
     * Get the managed file extension.
     */
    public function getFileExtension(): string;

    /**
     * Parse file and return the content as PHP array.
     */
    public function parseFile(string $filename): array;
}
