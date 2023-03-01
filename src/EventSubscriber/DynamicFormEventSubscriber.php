<?php

declare(strict_types=1);

namespace Wemxo\DynamicFormBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface DynamicFormEventSubscriber extends EventSubscriberInterface
{
    public function getName(): string;
}
