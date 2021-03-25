<?php namespace StudioAzura\Localize\Widgets;

class PluginList extends \RainLab\Builder\Widgets\PluginList
{
    public function __construct($controller, $alias)
    {
        parent::__construct($controller, $alias);

        $this->viewPath = [
            $this->viewPath, 
            '$/winter/builder/widgets/pluginlist/partials',
            '$/rainlab/builder/widgets/pluginlist/partials',
        ];
        $this->putSession('filter', 'all');
    }
}
