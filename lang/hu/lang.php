<?php

return [
    'plugin' => [
        'name' => 'YAMLocalizer',
        'description' => 'Lehetővé teszi Adminok számára Plugin lokalizációs fájlok szerkesztését közvetlenül az adminisztratív (backend) felületen',
    ],
    'localization' => [
        'select_file' => '-- válassza ki a nyelv-fájlt --',
        'files' => 'Nyelv fájl',
        'reset' => 'Eredeti értékek visszaállítása',
        'confirm_reset' => 'Visszaállítja a nyelv fájl eredeti állapotát?',
    ],
    'permissions' => [
        'manage_localizations' => [
            'tab' => 'Lokalizáció Plugin',
            'label' => 'Lokalizált plugin nyelv fájlok szerkesztése',
        ],
        'manage_settings' => [
            'label' => 'Lokalizációs beállítások szerkesztése',
        ],
    ],
    'settings' => [
        'label' => 'YAMLocalizer',
        'description' => 'Lokalizációs beállítások szerkesztése',
        'hide_builder' => 'RainLab.Builder menü elrejtése',
    ],
];
