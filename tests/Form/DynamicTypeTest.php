<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Tests\Form;

use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wemxo\DynamicFormBundle\Builder\DynamicFormBuilderInterface;
use Wemxo\DynamicFormBundle\Exception\FormConfigurationNotFound;
use Wemxo\DynamicFormBundle\Form\DynamicType;

class DynamicTypeTest extends TestCase
{
    /**
     * @throws FormConfigurationNotFound
     * @throws InvalidArgumentException
     */
    public function testBuildForm(): void
    {
        $dynamicFormBuilder = $this->createMock(DynamicFormBuilderInterface::class);
        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $dynamicFormBuilder
            ->expects($this->once())
            ->method('build')
            ->with('key', $formBuilder)
        ;
        $dynamicFormType = new DynamicType($dynamicFormBuilder);
        $dynamicFormType->buildForm($formBuilder, ['dynamic_key' => 'key']);
    }

    public function testConfigureOptions(): void
    {
        $dynamicFormType = new DynamicType(
            $this->createMock(DynamicFormBuilderInterface::class)
        );
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver
            ->expects($this->once())
            ->method('setDefined')
            ->with(['dynamic_key', 'dynamic_options'])
            ->willReturnSelf()
        ;
        $resolver
            ->expects($this->exactly(2))
            ->method('setAllowedTypes')
            ->withConsecutive(
                ['dynamic_key', 'string'],
                ['dynamic_options', ['null', 'array']]
            )
            ->willReturnSelf()
        ;
        $resolver
            ->expects($this->once())
            ->method('setRequired')
            ->with(['dynamic_key'])
            ->willReturnSelf()
        ;
        $dynamicFormType->configureOptions($resolver);
    }

    public function testGetBlockPrefix(): void
    {
        $dynamicFormType = new DynamicType(
            $this->createMock(DynamicFormBuilderInterface::class)
        );
        $this->assertEmpty($dynamicFormType->getBlockPrefix());
    }
}
