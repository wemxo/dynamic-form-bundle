<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests\DTO;

use PHPUnit\Framework\TestCase;
use Wemxo\DynamicFormBundle\DTO\DynamicFormConfiguration;

class DynamicFormConfigurationTest extends TestCase
{
    public function testAccessor(): void
    {
        $dynamicFormConfiguration = new DynamicFormConfiguration('key', ['test_config']);
        $this->assertSame('key', $dynamicFormConfiguration->getKey());
        $this->assertSame(['test_config'], $dynamicFormConfiguration->getConfig());
    }
}
