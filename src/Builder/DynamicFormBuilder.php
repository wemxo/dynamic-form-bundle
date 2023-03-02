<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\Builder;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Form\FormBuilderInterface;
use Wemxo\DynamicFormBundle\EventSubscriber\DynamicFormEventSubscriber;
use Wemxo\DynamicFormBundle\Loader\FormConfigurationLoaderInterface;

class DynamicFormBuilder implements DynamicFormBuilderInterface
{
    private iterable $eventSubscribers;

    private FormConfigurationLoaderInterface $formConfigurationLoader;

    public function __construct(
        iterable $eventSubscribers,
        FormConfigurationLoaderInterface $formConfigurationLoader
    ) {
        $this->eventSubscribers = $eventSubscribers;
        $this->formConfigurationLoader = $formConfigurationLoader;
    }

    public function build(string $key, FormBuilderInterface $builder): void
    {
        $configuration = $this->formConfigurationLoader->getConfiguration($key);
        $parsedConfiguration = (new Processor())->processConfiguration(new FormConfiguration(), ['form' => $configuration->getConfig()]);
        $subscribers = $parsedConfiguration['subscribers'] ?? [];
        $fields = $parsedConfiguration['fields'] ?? [];
        foreach ($fields as $fieldName => $fieldConfiguration) {
            $builder->add(
                $fieldName,
                $fieldConfiguration['type'],
                array_merge($fieldConfiguration['options'] ?? [], ['constraints' => $this->buildConstraints($fieldConfiguration)])
            );
        }

        foreach ($this->getSubscribers($subscribers) as $subscriber) {
            $builder->addEventSubscriber($subscriber);
        }
    }

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
