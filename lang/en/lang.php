<?php

return [
    'plugin' => [
        'name' => 'YAMLocalizer',
        'description' => 'Allow admins to override Plugin localization files from the backend UI',
    ],
    'localization' => [
        'select_file' => '-- select language file --',
        'files' => 'Language Files',
        'reset' => 'Reset Overrides',
        'confirm_reset' => 'Reset the override language file?',
    ],
    'permissions' => [
        'manage_localizations' => [
            'tab' => 'Localize Plugin',
            'label' => 'Manage Plugins Localization Files',
        ],
        'manage_settings' => [
            'label' => 'Manage Localization Settings',
        ],
    ],
    'settings' => [
        'label' => 'Localize',
        'description' => 'Manage localization settings',
        'hide_builder' => 'Hide RainLab.Builder Menu',
    ],
];
