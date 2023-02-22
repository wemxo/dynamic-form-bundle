<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Form;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wemxo\DynamicFormBundle\Builder\DynamicFormBuilderInterface;
use Wemxo\DynamicFormBundle\Exception\FormConfigurationNotFound;

class DynamicType extends AbstractType
{
    public function __construct(private readonly DynamicFormBuilderInterface $dynamicFormBuilder)
    {
    }

    /**
     * @throws FormConfigurationNotFound
     * @throws InvalidArgumentException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->dynamicFormBuilder->build($options['dynamic_key'], $builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefined(['dynamic_key', 'dynamic_options'])
            ->setAllowedTypes('dynamic_key', 'string')
            ->setAllowedTypes('dynamic_options', ['null', 'array'])
            ->setRequired(['dynamic_key'])
        ;
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
