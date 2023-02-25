<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests\Parser;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Wemxo\DynamicFormBundle\Parser\PhpFormConfigurationParser;

class PhpFormConfigurationParserTest extends TestCase
{
    /**
     * @dataProvider getFileDataProvider
     */
    public function testSupportFile(string $filename, bool $supported): void
    {
        $phpFormConfigurationParser = new PhpFormConfigurationParser();

        $this->assertSame($supported, $phpFormConfigurationParser->supportFile($filename));
    }

    public function testGetFileExtension(): void
    {
        $phpFormConfigurationParser = new PhpFormConfigurationParser();

        $this->assertSame('php', $phpFormConfigurationParser->getFileExtension());
    }

    public function testParseFile(): void
    {
        $phpFormConfigurationParser = new PhpFormConfigurationParser();
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

        $this->assertSame($config, $phpFormConfigurationParser->parseFile(__DIR__ . '/../mocks/form_config.php'));
    }

    public function getFileDataProvider(): \Generator
    {
        yield [__DIR__ . '/../mocks/form_config.yaml', false];

        yield [__DIR__ . '/../mocks/form_config.php', true];
    }
}
