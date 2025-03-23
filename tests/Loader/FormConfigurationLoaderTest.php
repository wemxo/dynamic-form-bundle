<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests\Loader;

use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\Cache\CacheInterface;
use Wemxo\DynamicFormBundle\DTO\DynamicFormConfiguration;
use Wemxo\DynamicFormBundle\Exception\FormConfigurationNotFound;
use Wemxo\DynamicFormBundle\Loader\FormConfigurationLoader;
use Wemxo\DynamicFormBundle\Parser\PhpFormConfigurationParser;
use Wemxo\DynamicFormBundle\Parser\YamlFormConfigurationParser;

class FormConfigurationLoaderTest extends TestCase
{
    public function testAddConfigurationPath(): void
    {
        $formConfigurationLoader = new FormConfigurationLoader([]);
        $reflectionClass = new \ReflectionClass($formConfigurationLoader);
        $reflectionProperty = $reflectionClass->getProperty('configPaths');
        $reflectionProperty->setAccessible(true);
        $this->assertEmpty($reflectionProperty->getValue($formConfigurationLoader));
        $formConfigurationLoader->addConfigurationPath('test path');
        $this->assertCount(1, $reflectionProperty->getValue($formConfigurationLoader));
    }

    public function testSetCache(): void
    {
        $formConfigurationLoader = new FormConfigurationLoader([]);
        $cache = $this->createMock(CacheInterface::class);
        $reflectionClass = new \ReflectionClass($formConfigurationLoader);
        $reflectionProperty = $reflectionClass->getProperty('cache');
        $reflectionProperty->setAccessible(true);
        $this->assertNull($reflectionProperty->getValue($formConfigurationLoader));
        $formConfigurationLoader->setCache($cache);
        $this->assertSame($cache, $reflectionProperty->getValue($formConfigurationLoader));
    }

    public function testSetRecursive(): void
    {
        $formConfigurationLoader = new FormConfigurationLoader([]);
        $reflectionClass = new \ReflectionClass($formConfigurationLoader);
        $reflectionProperty = $reflectionClass->getProperty('recursive');
        $reflectionProperty->setAccessible(true);
        $this->assertTrue($reflectionProperty->getValue($formConfigurationLoader));
        $formConfigurationLoader->setRecursive(false);
        $this->assertFalse($reflectionProperty->getValue($formConfigurationLoader));
    }

    public function testGetConfigurationParserNotFound(): void
    {
        $formConfigurationLoader = new FormConfigurationLoader([]);
        $formConfigurationLoader->addConfigurationPath(__DIR__ . '/../mocks');
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf(
            'Unable to find form configuration parser for %s',
            realpath(__DIR__ . '/../mocks/form_config.php')
        ));
        $formConfigurationLoader->getConfiguration('test');
    }

    /**
     * @dataProvider getConfigurationNotFoundDataProvider
     *
     * @throws InvalidArgumentException
     */
    public function testGetConfigurationNotFound(bool $recursive, array $configPaths, array $parsers, ?CacheInterface $cache): void
    {
        $formConfigurationLoader = new FormConfigurationLoader($parsers);
        $formConfigurationLoader->setRecursive($recursive);
        $formConfigurationLoader->setCache($cache);
        foreach ($configPaths as $configPath) {
            $formConfigurationLoader->addConfigurationPath($configPath);
        }

        $this->expectException(FormConfigurationNotFound::class);
        $this->expectExceptionMessage('Dynamic form configuration not found !');
        $this->expectExceptionCode(901);
        $formConfigurationLoader->getConfiguration('test');
    }

    /**
     * @dataProvider getConfigurationDataProvider
     *
     * @throws InvalidArgumentException
     * @throws FormConfigurationNotFound
     */
    public function testGetConfiguration(bool $recursive, array $configPaths, array $parsers, ?CacheInterface $cache): void
    {
        $formConfigurationLoader = new FormConfigurationLoader($parsers);
        $formConfigurationLoader->setRecursive($recursive);
        $formConfigurationLoader->setCache($cache);
        foreach ($configPaths as $configPath) {
            $formConfigurationLoader->addConfigurationPath($configPath);
        }

        $formConfiguration = $formConfigurationLoader->getConfiguration('form_config#config');
        $this->assertInstanceOf(DynamicFormConfiguration::class, $formConfiguration);
        $this->assertSame('form_config#config', $formConfiguration->getKey());
        $this->assertNotEmpty($formConfiguration->getConfig());
        $config = $formConfiguration->getConfig();
        $this->assertNull($config['subscribers']);
        $this->assertNotEmpty($config['fields']);
        $this->assertIsArray($config['fields']);
        $this->assertArrayHasKey('field', $config['fields']);
        $fieldConfiguration = $config['fields']['field'];
        $this->assertNotEmpty($fieldConfiguration);
        $this->assertIsArray($fieldConfiguration);
        $this->assertArrayHasKey('type', $fieldConfiguration);
        $this->assertArrayHasKey('options', $fieldConfiguration);
        $this->assertArrayHasKey('constraints', $fieldConfiguration);
        $this->assertSame(TextType::class, $fieldConfiguration['type']);
        $this->assertNull($fieldConfiguration['constraints']);
        $this->assertIsArray($fieldConfiguration['options']);
        $this->assertNotEmpty($fieldConfiguration['options']);
        $this->assertArrayHasKey('label', $fieldConfiguration['options']);
        $this->assertSame('labelValue', $fieldConfiguration['options']['label']);
    }

    public function getConfigurationNotFoundDataProvider(): \Generator
    {
        yield [
            true,
            [__DIR__ . '/../mocks'],
            [new YamlFormConfigurationParser(), new PhpFormConfigurationParser()],
            null,
        ];

        yield [
            true,
            [__DIR__ . '/../mocks'],
            [new YamlFormConfigurationParser(), new PhpFormConfigurationParser()],
            new NullAdapter(),
        ];

        yield [
            false,
            [__DIR__ . '/../mocks'],
            [new YamlFormConfigurationParser(), new PhpFormConfigurationParser()],
            null,
        ];

        yield [
            false,
            [],
            [new YamlFormConfigurationParser(), new PhpFormConfigurationParser()],
            null,
        ];
    }

    public function getConfigurationDataProvider(): \Generator
    {
        yield [
            true,
            [__DIR__ . '/../mocks'],
            [new YamlFormConfigurationParser(), new PhpFormConfigurationParser()],
            null,
        ];

        yield [
            true,
            [__DIR__ . '/../mocks'],
            [new YamlFormConfigurationParser(), new PhpFormConfigurationParser()],
            new NullAdapter(),
        ];

        yield [
            false,
            [__DIR__ . '/../mocks'],
            [new YamlFormConfigurationParser(), new PhpFormConfigurationParser()],
            null,
        ];
    }
}
