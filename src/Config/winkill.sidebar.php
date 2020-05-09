<?php

return [
    'winkill' => [
        'name' => 'winkill::winkill.label',
        'label' => 'winkill::winkill.label',
        'icon' => 'fa-money',
        'route_segment' => 'winkill',
        'route'         => 'winkill.view',
        'entries' => [
            'winkill' => [
                'name' => 'winkill::winkill.label',
                'label' => 'winkill::winkill.label',
                'icon' => 'fa-money ',
                'route' => 'winkill.view',
                'permission' => 'winkill.view',
            ],
            'settings' => [
                'name' => 'winkill::winkill.settings',
                'label' => 'winkill::winkill.settings',
                'icon' => 'fa-cogs',
                'route' => 'winkill.settings',
                'permission' => 'winkill.settings',
            ],
        ],
    ],
];
