<?php namespace StudioAzura\Localize;

use Backend;
use Event;
use System\Classes\PluginBase;
use StudioAzura\Localize\Classes\LocalizationModel;

/**
 * Plugin Information File
 */
class Plugin extends PluginBase
{
    public $require = ['RainLab.Builder'];

    public function pluginDetails()
    {
        return [
            'name'        => 'studioazura.localize::lang.plugin.name',
            'description' => 'studioazura.localize::lang.plugin.description',
            'author'      => 'Marc Jauvin',
            'icon'        => 'icon-language'
        ];
    }

    public function registerPermissions()
    {
        return [
            'studioazura.localize.manage_localizations' => [
                'tab' => 'studioazura.localize::lang.permissions.tab',
                'label' => 'studioazura.localize::lang.permissions.label',
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'localize' => [
                'label'       => 'Localize',
                'url'         => Backend::url('studioazura/localize'),
                'icon'        => 'icon-globe',
                'order'       => 400,

                'sideMenu' => [
                    'localization' => [
                        'label'       => 'rainlab.builder::lang.localization.menu_label',
                        'icon'        => 'icon-globe',
                        'url'         => 'javascript:;',
                        'attributes'  => ['data-menu-item'=>'localization'],
                        'permissions' => ['studioazura.localize.manage_localizations']
                    ]
                ]

            ]
        ];
    }
}
