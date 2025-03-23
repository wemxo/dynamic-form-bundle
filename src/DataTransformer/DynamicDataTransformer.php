<?php

namespace Wemxo\DynamicFormBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

interface DynamicDataTransformer extends DataTransformerInterface
{
    public function getName(): string;
}
