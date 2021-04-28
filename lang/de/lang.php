<?php

return [
    'plugin' => [
        'name' => 'YAMLocalizer',
        'description' => 'Erlaubt Admins die Lokalisierungsdateien zu überschreiben direkt aus dem Backend des Winter CMS.',
    ],
    'localization' => [
        'add_missing_strings' => 'Füge fehlende Strings ein',
        'confirm_reset' => 'Die Überschreibung-Sprachdatei zurücksetzen?',
        'files' => 'Sprachdateien',
        'language' => 'Sprache',
        'reset' => 'Überschreibungen zurücksetzen',
        'select_file' => '-- wähle die Sprachdatei aus --',
    ],
    'languagelist' => [
        'add' => 'Hinzufügen',
        'search' => 'Suchen',
    ],
    'permissions' => [
        'manage_localizations' => [
            'tab' => 'Lokaliserung Plugin',
            'label' => 'Verwalte die Plugins Lokalisierungs Dateien',
        ],
        'manage_settings' => [
            'label' => 'Manage Localization Settings',
        ],
    ],
    'settings' => [
        'label' => 'YAMLocalizer',
        'description' => 'Festlegen von Plugin-Einstellungen',
        'hide_builder' => 'Verstecke den RainLab.Builder Menüeintrag',
    ],
];
