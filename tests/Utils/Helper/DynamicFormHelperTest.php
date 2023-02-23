<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests;

use PHPUnit\Framework\TestCase;
use Wemxo\DynamicFormBundle\Utils\Helper\DynamicFormHelper;

class DynamicFormHelperTest extends TestCase
{
    public function testConfigKey(): void
    {
        $this->assertSame(
            'one#two#three',
            DynamicFormHelper::configKey('one', 'two', 'three')
        );
    }
}
