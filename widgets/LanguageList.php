<?php namespace R4L\Localize\Widgets;

use R4L\Localize\Classes\LocalizationModel;

class LanguageList extends \RainLab\Builder\Widgets\LanguageList
{
    public function __construct($controller, $alias)
    {
        parent::__construct($controller, $alias);

        $this->viewPath = '$/rainlab/builder/widgets/languagelist/partials';
    }

    protected function getLanguageList($pluginCode)
    {
        $result = LocalizationModel::listPluginLanguages($pluginCode);

        return $result;
    }
}
