<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Builder;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Form\FormBuilderInterface;
use Wemxo\DynamicFormBundle\DataTransformer\DynamicDataTransformer;
use Wemxo\DynamicFormBundle\EventSubscriber\DynamicFormEventSubscriber;
use Wemxo\DynamicFormBundle\Loader\FormConfigurationLoaderInterface;

class DynamicFormBuilder implements DynamicFormBuilderInterface
{
    private iterable $eventSubscribers;

    private iterable $dataTransformers;

    private FormConfigurationLoaderInterface $formConfigurationLoader;

    public function __construct(
        iterable $eventSubscribers,
        iterable $dataTransformers,
        FormConfigurationLoaderInterface $formConfigurationLoader
    ) {
        $this->eventSubscribers = $eventSubscribers;
        $this->dataTransformers = $dataTransformers;
        $this->formConfigurationLoader = $formConfigurationLoader;
    }

    public function build(string $key, FormBuilderInterface $builder): void
    {
        $configuration = $this->formConfigurationLoader->getConfiguration($key);
        $parsedConfiguration = (new Processor())->processConfiguration(new FormConfiguration(), ['form' => $configuration->getConfig()]);
        $subscribers = $parsedConfiguration['subscribers'] ?? [];
        $globalDataTransformers = $parsedConfiguration['transformers'] ?? [];
        $fieldsTransformers = [];
        $fields = $parsedConfiguration['fields'] ?? [];
        foreach ($fields as $fieldName => $fieldConfiguration) {
            if (isset($fieldConfiguration['transformers'])) {
                $fieldsTransformers[$fieldName] = $fieldConfiguration['transformers'];
            }

            $builder->add(
                $fieldName,
                $fieldConfiguration['type'],
                array_merge($fieldConfiguration['options'] ?? [], ['constraints' => $this->buildConstraints($fieldConfiguration)])
            );
        }

        foreach ($this->getSubscribers($subscribers) as $subscriber) {
            $builder->addEventSubscriber($subscriber);
        }

        foreach ($this->getTransformers($globalDataTransformers) as $transformer) {
            $builder->addModelTransformer($transformer);
        }

        foreach ($fieldsTransformers as $field => $transformers) {
            if (!$builder->has($field)) {
                continue;
            }

            foreach ($this->getTransformers($transformers) as $transformer) {
                $builder
                    ->get($field)
                    ->addModelTransformer($transformer)
                ;
            }
        }
    }

    /**
     * @return DynamicDataTransformer[]
     */
    private function getTransformers(array $transformersNames): array
    {
        if (empty($transformersNames)) {
            return [];
        }

        $transformers = [];
        foreach ($this->dataTransformers as $transformer) {
            if (!$transformer instanceof DynamicDataTransformer || !in_array($transformer->getName(), $transformersNames)) {
                continue;
            }

            $transformers[] = $transformer;
        }

        return $transformers;
    }

    /**
     * @return DynamicFormEventSubscriber[]
     */
    private function getSubscribers(array $subscribersNames): array
    {
        if (empty($subscribersNames)) {
            return [];
        }

        $subscribers = [];
        foreach ($this->eventSubscribers as $subscriber) {
            if (!$subscriber instanceof DynamicFormEventSubscriber || !in_array($subscriber->getName(), $subscribersNames)) {
                continue;
            }

            $subscribers[] = $subscriber;
        }

        return $subscribers;
    }

    private function buildConstraints(array $fieldConfiguration): array
    {
        if (empty($fieldConfiguration['constraints'])) {
            return [];
        }

        $constraints = [];
        foreach ($fieldConfiguration['constraints'] as $constraintConfig) {
            $constraintClass = $constraintConfig['class'];
            $constraints[] = new $constraintClass($constraintConfig['options']);
        }

        return $constraints;
    }
}
