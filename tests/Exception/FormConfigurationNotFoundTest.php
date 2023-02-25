<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Wemxo\DynamicFormBundle\Exception\FormConfigurationNotFound;

class FormConfigurationNotFoundTest extends TestCase
{
    public function testAccessor(): void
    {
        $exception = new FormConfigurationNotFound('key');
        $this->assertSame('key', $exception->getConfigurationKey());
    }
}
