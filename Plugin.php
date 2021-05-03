<?php namespace StudioAzura\Localize;

use Backend;
use Event;
use StudioAzura\Localize\Models\Settings;
use StudioAzura\Localize\Classes\LocalizationModel;
use System\Classes\PluginBase;

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
            'icon'        => 'icon-language',
            'iconSvg'     => 'plugins/studioazura/localize/assets/icons/icon.svg',
        ];
    }

    public function register()
    {
        // force sensible umask
        umask(0002);

        $this->registerConsoleCommand('winter.lang', 'StudioAzura\Localize\Console\WinterLang');
    }

    public function boot()
    {
        Event::listen('backend.menu.extendItems', function($manager) {
            if (Settings::get('hide_builder', true)) {
                $manager->removeMainMenuItem('RainLab.Builder', 'builder');
            }
        });
    }

    public function registerPermissions()
    {
        return [
            'studioazura.localize.manage_localizations' => [
                'tab' => 'studioazura.localize::lang.permissions.manage_localizations.tab',
                'label' => 'studioazura.localize::lang.permissions.manage_localizations.label',
            ],
            'studioazura.localize.manage_settings' => [
                'label' => 'studioazura.localize::lang.permissions.manage_settings.label',
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'localize' => [
                'label'       => 'Localize',
                'url'         => Backend::url('studioazura/localize'),
                'icon'        => 'icon-language',
                'iconSvg'     => 'plugins/studioazura/localize/assets/icons/icon.svg',
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

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'studioazura.localize::lang.settings.label',
                'description' => 'studioazura.localize::lang.settings.description',
                'icon'        => 'icon-language',
                'class'       => 'StudioAzura\Localize\Models\Settings',
                'keywords'    => 'localize settings config',
                'order'       => 500,
                'permissions' => ['studioazura.localize.manage_settings'],
                'category' => 'system::lang.system.categories.misc',
              ],
        ];
    }

}
