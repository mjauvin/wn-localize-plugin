<?php namespace R4L\Localize;

use Backend;
use Event;
use System\Classes\PluginBase;
use R4L\Localize\Classes\LocalizationModel;

/**
 * Plugin Information File
 */
class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Localize',
            'description' => 'Allow admins to override Plugin localization files from the backend UI',
            'author'      => 'Marc Jauvin',
            'icon'        => 'icon-language'
        ];
    }

    public function boot()
    {
        Event::listen('backend.form.extendFieldsBefore', function ($widget) {
            if (!$widget->model instanceof LocalizationModel) {
                return;
            }

            $widget->fields['toolbar']['path'] = '$/r4l/localize/behaviors/indexlocalizationoperations/partials/_toolbar.htm';
        });
    }

    public function registerPermissions()
    {
        return [
            'r4l.localize.manage_localizations' => [
                'tab' => 'Localize Plugin',
                'label' => 'Manage Plugins Localization Files'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'localize' => [
                'label'       => 'Localize',
                'url'         => Backend::url('r4l/localize'),
                'icon'        => 'icon-globe',
                'order'       => 400,

                'sideMenu' => [
                    'localization' => [
                        'label'       => 'rainlab.builder::lang.localization.menu_label',
                        'icon'        => 'icon-globe',
                        'url'         => 'javascript:;',
                        'attributes'  => ['data-menu-item'=>'localization'],
                        'permissions' => ['r4l.localize.manage_localizations']
                    ]
                ]

            ]
        ];
    }
}
