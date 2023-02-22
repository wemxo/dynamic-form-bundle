<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

#[AutoconfigureTag(name: 'wemxo.dynamic_form.event_subscriber')]
interface DynamicFormEventSubscriber extends EventSubscriberInterface
{
    public function getName(): string;
}
