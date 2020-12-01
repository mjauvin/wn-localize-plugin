<?php namespace StudioAzura\Localize\Controllers;

use BackendMenu;
use Lang;

use StudioAzura\Localize\Widgets\PluginList;
use StudioAzura\Localize\Widgets\LanguageList;

class Index extends \RainLab\Builder\Controllers\Index
{
    public $implement = [
        'RainLab.Builder.Behaviors.IndexPluginOperations',
        'StudioAzura.Localize.Behaviors.IndexLocalizationOperations',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('StudioAzura.Localize', 'localize', 'localization');

        $this->bodyClass = 'compact-container';
        $this->pageTitle = Lang::get('rainlab.builder::lang.plugin.name');

        new PluginList($this, 'pluginList');
        new LanguageList($this, 'languageList');
    }
}
