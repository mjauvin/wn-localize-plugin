<?php namespace R4L\LocalizePlugin;

use Backend;
use System\Classes\PluginBase;

/**
 * LocalizePlugin Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'LocalizePlugin',
            'description' => 'No description provided yet...',
            'author'      => 'R4L',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'R4L\LocalizePlugin\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'r4l.localizeplugin.some_permission' => [
                'tab' => 'LocalizePlugin',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'localizeplugin' => [
                'label'       => 'Localize',
                'url'         => Backend::url('r4l/localizeplugin'),
                'icon'        => 'icon-globe',
                'order'       => 400,

                'sideMenu' => [
                    'localization' => [
                        'label'       => 'rainlab.builder::lang.localization.menu_label',
                        'icon'        => 'icon-globe',
                        'url'         => 'javascript:;',
                        'attributes'  => ['data-menu-item'=>'localization'],
                        'permissions' => ['rainlab.builder.manage_plugins']
                    ]
                ]

            ]
        ];
    }

}
