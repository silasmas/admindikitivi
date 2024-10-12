<?php

use Joaopaulolndev\FilamentGeneralSettings\Enums\TypeFieldEnum;

return [
    'show_application_tab' => true,
    'show_logo_and_favicon' => false,
    'show_analytics_tab' => true,
    'show_seo_tab' => true,
    'show_email_tab' => true,
    'show_social_networks_tab' => true,
    'expiration_cache_config_time' => 60,
    'custom_tabs' => [
        'more_configs' => [
            'label' => 'More Configs',
            'icon' => 'heroicon-o-plus-circle',
            'columns' => 1,
            'fields' => [
                'custom_field_1' => [
                    'type' => TypeFieldEnum::Text->value,
                    'label' => 'Custom Textfield 1',
                    'placeholder' => 'Custom Field 1',
                    'required' => true,
                    'rules' => 'required|string|max:255',
                ],
                'custom_field_2' => [
                    'type' => TypeFieldEnum::Select->value,
                    'label' => 'Custom Select 2',
                    'placeholder' => 'Select',
                    'required' => true,
                    'options' => [
                        'option_1' => 'Option 1',
                        'option_2' => 'Option 2',
                        'option_3' => 'Option 3',
                    ],
                ],
                'custom_field_3' => [
                    'type' => TypeFieldEnum::Textarea->value,
                    'label' => 'Custom Textarea 3',
                    'placeholder' => 'Textarea',
                    'rows' => '3',
                    'required' => true,
                ],
                'custom_field_4' => [
                    'type' => TypeFieldEnum::Datetime->value,
                    'label' => 'Custom Datetime 4',
                    'placeholder' => 'Datetime',
                    'seconds' => false,
                ],
                'custom_field_5' => [
                    'type' => TypeFieldEnum::Boolean->value,
                    'label' => 'Custom Boolean 5',
                    'placeholder' => 'Boolean'
                ],
            ]
        ],
    ]
];
