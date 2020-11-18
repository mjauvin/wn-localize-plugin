<?php

return [
    'plugin' => [
        'name' => 'Localize',
        'description' => 'Allow admins to override Plugin localization files from the backend UI',
    ],
    'localization' => [
        'select_file' => '-- select language file --',
        'files' => 'Language Files',
        'reset' => 'Reset Overrides',
        'confirm_reset' => 'Reset the override language file?',
    ],
    'permissions' => [
        'tab' => 'Localize Plugin',
        'label' => 'Manage Plugins Localization Files',
    ],
];
