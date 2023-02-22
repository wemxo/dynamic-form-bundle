<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Parser;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: 'wemxo.dynamic_form.form_configuration_parser')]
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
