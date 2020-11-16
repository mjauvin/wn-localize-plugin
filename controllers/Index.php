<?php namespace R4L\LocalizePlugin\Controllers;

use BackendMenu;
use Config;

use R4L\LocalizePlugin\Widgets\PluginList;
use RainLab\Builder\Widgets\LanguageList;

class Index extends \RainLab\Builder\Controllers\Index
{
    public $implement = [
        'RainLab.Builder.Behaviors.IndexPluginOperations',
        'R4L.LocalizePlugin.Behaviors.IndexLocalizationOperations',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('R4L.LocalizePlugin', 'localizeplugin', 'localization');

        $this->bodyClass = 'compact-container';
        $this->pageTitle = 'rainlab.builder::lang.plugin.name';

        new PluginList($this, 'pluginList');
        new LanguageList($this, 'languageList');
    }
}
