<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Wemxo\DynamicFormBundle\Parser\YamlFormConfigurationParser;

class YamlFormConfigurationParserTest extends TestCase
{
    /**
     * @dataProvider getFileDataProvider
     */
    public function testSupportFile(string $filename, bool $supported): void
    {
        $phpFormConfigurationParser = new YamlFormConfigurationParser();

        $this->assertSame($supported, $phpFormConfigurationParser->supportFile($filename));
    }

    public function testGetFileExtension(): void
    {
        $phpFormConfigurationParser = new YamlFormConfigurationParser();

        $this->assertSame('yaml', $phpFormConfigurationParser->getFileExtension());
    }

    public function testParseFile(): void
    {
        $phpFormConfigurationParser = new YamlFormConfigurationParser();
        $config = [
            'config' => [
                'form' => [
                    'subscribers' => null,
                    'fields' => [
                        'field' => [
                            'type' => TextType::class,
                            'options' => [
                                'label' => 'labelValue',
                            ],
                            'constraints' => null,
                        ],
                    ],
                ],
            ],
        ];

        $this->assertSame($config, $phpFormConfigurationParser->parseFile(__DIR__ . '/../mocks/form_config.yaml'));
    }

    public function getFileDataProvider(): \Generator
    {
        yield [__DIR__ . '/../mocks/form_config.yaml', true];

        yield [__DIR__ . '/../mocks/form_config.php', false];
    }
}
