# dynamic-form-bundle

![GitHub](https://img.shields.io/github/license/wemxo/dynamic-form-bundle) ![build](https://github.com/wemxo/dynamic-form-bundle/actions/workflows/build.yaml/badge.svg?branch=master) ![img](https://gist.githubusercontent.com/zta9taw/210e582b8ded2b1013aeab92bad9f5fe/raw/coverage.svg) ![GitHub all releases](https://img.shields.io/packagist/dt/wemxo/dynamic-form-bundle)

Dynamic form bundle gives you the ability to create forms dynamically based on a given configuration.

## Requirements / Dependencies

- **PHP 7.4 or higher**
- **symfony/framework-bundle 5.0 or higher**
- **symfony/form 5.0 or higher**
- **symfony/finder 5.0 or higher**

## Usage

### Installation

Install the latest version

`composer require wemxo/dynamic-form-bundle`

### Activation

Enable the bundle in `config/bundle.php`

```php
<?php

return [
    /* ... */
    Wemxo\DynamicFormBundle\DynamicFormBundle::class => ['all' => true],
    /* ... */
];

```

### Configuration

Add bundle configuration

```yaml
# config/packages/dynamic_form.yaml

dynamic_form:
    recursive: true # Browse config folders recursively
    config_paths: # Files configuration paths
        - '%kernel.project_dir%/config/dynamic_form'
when@prod:
    framework:
        cache:
            pools:
                dynamic_form_pool_cache:
                    adapter: cache.adapter.redis
    dynamic_form:
        cache_pool: dynamic_form_pool_cache # Use cache 'dynamic_form_pool_cache' to store parsed configuration (recommended in production)
```

### Example of form configuration

#### YAML format

```yaml
# config/dynamic_form/address.yaml
france:
    form:
        subscribers: ~
        fields:
            address: &address
                type: &textType Symfony\Component\Form\Extension\Core\Type\TextType
                options:
                    label: Address
                    required: true
                    attr:
                        placeholder: Enter your address
                constraints:
                    - class: &notBlank Symfony\Component\Validator\Constraints\NotBlank
                      options:
                          message: You must enter your address
            addressComplement: &addressComplement
                type: *textType
                options:
                    label: Address complement
                    required: false
                    attr:
                        placeholder: Enter your address complement
                constraints: ~
            postalCode: &postalCode
                type: *textType
                options:
                    label: Postal code
                    required: true
                    attr:
                        placeholder: Enter your postal code
                constraints:
                    - class: *notBlank
                      options:
                          message: You must enter your postal code
italy:
    form:
        subscribers: ~
        fields:
            address: *address
            addressComplement: *addressComplement
            postalCode: *postalCode
            department: &department
                type: *textType
                options:
                    label: Department
                    required: true
                    attr:
                        placeholder: Enter your department
                constraints:
                    - class: *notBlank
                      options:
                          message: You must enter your department
morocco:
    form:
        subscribers: ~
        fields:
            address: *address
            postalCode: *postalCode
            city:
                type: *textType
                options:
                    label: City
                    required: true
                    attr:
                        placeholder: Enter your city
                constraints:
                    - class: *notBlank
                      options:
                          message: You must enter your city
```

#### PHP format

```php
<?php

return [
    'france' => [
        'form' => [
            'subscribers' => NULL,
            'fields' => [
                'address' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Address',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your address',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your address',
                            ],
                        ],
                    ],
                ],
                'addressComplement' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Address complement',
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'Enter your address complement',
                        ],
                    ],
                    'constraints' => NULL,
                ],
                'postalCode' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Postal code',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your postal code',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your postal code',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'italy' => [
        'form' => [
            'subscribers' => NULL,
            'fields' => [
                'address' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Address',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your address',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your address',
                            ],
                        ],
                    ],
                ],
                'addressComplement' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Address complement',
                        'required' => false,
                        'attr' => [
                            'placeholder' => 'Enter your address complement',
                        ],
                    ],
                    'constraints' => NULL,
                ],
                'postalCode' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Postal code',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your postal code',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your postal code',
                            ],
                        ],
                    ],
                ],
                'department' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Department',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your department',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your department',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'morocco' => [
        'form' => [
            'subscribers' => NULL,
            'fields' => [
                'address' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Address',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your address',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your address',
                            ],
                        ],
                    ],
                ],
                'postalCode' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'Postal code',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your postal code',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your postal code',
                            ],
                        ],
                    ],
                ],
                'city' => [
                    'type' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
                    'options' => [
                        'label' => 'City',
                        'required' => true,
                        'attr' => [
                            'placeholder' => 'Enter your city',
                        ],
                    ],
                    'constraints' => [
                        0 => [
                            'class' => 'Symfony\\Component\\Validator\\Constraints\\NotBlank',
                            'options' => [
                                'message' => 'You must enter your city',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
```

### Create form

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wemxo\DynamicFormBundle\Form\DynamicType;
use Wemxo\DynamicFormBundle\Utils\Helper\DynamicFormHelper;

class AppController extends AbstractController
{
    #[Route(name: 'app_home')]
    public function home(FormFactoryInterface $formFactory): Response
    {
        $user = $this->getUser();
        $countryLabel = $user->getCountry()->getLabel();
        $form = $formFactory->create(DynamicType::class, null, [
            'dynamic_key' => DynamicFormHelper::configKey('address', $countryLabel),
        ]);

        return $this->render('app/home.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
```

### Create form with block prefix

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wemxo\DynamicFormBundle\Form\DynamicType;
use Wemxo\DynamicFormBundle\Utils\Helper\DynamicFormHelper;

class AppController extends AbstractController
{
    #[Route(name: 'app_home')]
    public function home(FormFactoryInterface $formFactory): Response
    {
        $user = $this->getUser();
        $countryLabel = $user->getCountry()->getLabel();
        $form = $formFactory->createNamed('my_custom_prefix', DynamicType::class, null, [
            'dynamic_key' => DynamicFormHelper::configKey('address', $countryLabel),
        ]);

        return $this->render('app/home.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
```

### Use form event subscriber

1 - Create a class by implementing the interface `Wemxo\DynamicFormBundle\EventSubscriber\DynamicFormEventSubscriber`.

​	1.1 - Implement `getName` function.

​	1.2 - Implement `getSubscribedEvents` function.

```php
<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Wemxo\DynamicFormBundle\EventSubscriber\DynamicFormEventSubscriber;

class AddressFormEventSubscriber implements DynamicFormEventSubscriber
{

    public function getName(): string
    {
        return 'address_event_subscriber';
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'onPreSetData',
        ];
    }
    
    public function onPreSetData(FormEvent $event): void
    {
        // do something.
    }
}
```

​	1.3 - Update form configuration

```yaml
france:
    form:
        subscribers:
            - address_event_subscriber # The value returned from the getName function.
        fields: []
```
