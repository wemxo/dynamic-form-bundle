<?php

return [
    'config' => [
        'form' => [
            'subscribers' => null,
            'fields' => [
                'field' => [
                    'type' => \Symfony\Component\Form\Extension\Core\Type\TextType::class,
                    'options' => [
                        'label' => 'labelValue',
                    ],
                    'constraints' => null,
                ]
            ],
        ],
    ],
];
