<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Utils\Helper;

class DynamicFormHelper
{
    public static function configKey(string ...$parts): string
    {
        return implode('#', $parts);
    }
}
