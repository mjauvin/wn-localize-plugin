<?php namespace R4L\Localize\Controllers;

use BackendMenu;

use R4L\Localize\Widgets\PluginList;
use R4L\Localize\Widgets\LanguageList;

class Index extends \RainLab\Builder\Controllers\Index
{
    public $implement = [
        'RainLab.Builder.Behaviors.IndexPluginOperations',
        'R4L.Localize.Behaviors.IndexLocalizationOperations',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('R4L.Localize', 'localize', 'localization');

        $this->bodyClass = 'compact-container';
        $this->pageTitle = 'rainlab.builder::lang.plugin.name';

        new PluginList($this, 'pluginList');
        new LanguageList($this, 'languageList');
    }
}
