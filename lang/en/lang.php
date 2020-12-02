<?php

return [
    'plugin' => [
        'name' => 'YAMLocalizer',
        'description' => 'Allow admins to override Plugin localization files from the backend UI',
    ],
    'localization' => [
        'add_missing_strings' => 'Add missing strings',
        'confirm_reset' => 'Reset the override language file?',
        'files' => 'Language Files',
        'language' => 'Language',
        'reset' => 'Reset Overrides',
        'select_file' => '-- select language file --',
    ],
    'languagelist' => [
        'add' => 'Add',
        'search' => 'Search',
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
        'label' => 'YAMLocalizer',
        'description' => 'Manage localization settings',
        'hide_builder' => 'Hide RainLab.Builder Menu',
    ],
];
