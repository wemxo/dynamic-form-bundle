<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Wemxo\DynamicFormBundle\Builder\DynamicFormBuilder;
use Wemxo\DynamicFormBundle\DTO\DynamicFormConfiguration;
use Wemxo\DynamicFormBundle\EventSubscriber\DynamicFormEventSubscriber;
use Wemxo\DynamicFormBundle\Loader\FormConfigurationLoaderInterface;

class DynamicFormBuilderTest extends TestCase
{
    /**
     * @dataProvider getBuildDataProvider
     */
    public function testBuild(bool $declareSubscribers, bool $configureConstraints, bool $configureSubscribers): void
    {
        $formConfigurationLoader = $this->createMock(FormConfigurationLoaderInterface::class);
        $dynamicFormConfiguration = $this->createMock(DynamicFormConfiguration::class);
        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $eventSubscribers = ['fake_subscriber'];
        if ($declareSubscribers) {
            $eventSubscriber = $this->createMock(DynamicFormEventSubscriber::class);
            $eventSubscriber
                ->expects($this->once())
                ->method('getName')
                ->willReturn('test_subscriber')
            ;
            $eventSubscribers[] = $eventSubscriber;
            $formBuilder
                ->expects($this->once())
                ->method('addEventSubscriber')
                ->with($eventSubscriber)
                ->willReturnSelf()
            ;
        }

        $configuration = [
            'fields' => [
                'testField' => [
                    'type' => TextType::class,
                    'options' => [
                        'label' => 'Test label',
                    ],
                ],
            ],
        ];
        if ($declareSubscribers) {
            $configuration['subscribers'] = [
                'test_subscriber',
            ];
        }

        if ($configureConstraints) {
            $configuration['fields']['testField']['constraints'] = [
                [
                    'class' => NotBlank::class,
                ]
            ];
        }
        $dynamicFormConfiguration
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn($configuration)
        ;
        $formBuilder
            ->expects($this->once())
            ->method('add')
            ->with(
                'testField',
                TextType::class,
                [
                    'label' => 'Test label',
                    'constraints' => $configureConstraints ? [new NotBlank()] : [],
                ]
            )
            ->willReturnSelf()
        ;
        $formConfigurationLoader
            ->expects($this->once())
            ->method('getConfiguration')
            ->with('key')
            ->willReturn($dynamicFormConfiguration)
        ;
        $dynamicFormBuilder = new DynamicFormBuilder(
            $eventSubscribers,
            [],
            $formConfigurationLoader
        );
        $dynamicFormBuilder->build('key', $formBuilder);
    }

    public function getBuildDataProvider(): \Generator
    {
        yield [true, true, true];

        yield [true, true, false];

        yield [true, false, false];

        yield [false, false, false];

        yield [false, false, true];

        yield [false, true, true];

        yield [true, false, true];

        yield [false, true, false];
    }
}
