<?php namespace R4L\LocalizePlugin\Widgets;

class PluginList extends \RainLab\Builder\Widgets\PluginList
{
    public function __construct($controller, $alias)
    {
        parent::__construct($controller, $alias);

        $this->viewPath = [
            $this->viewPath, 
            '$/rainlab/builder/widgets/pluginlist/partials',
        ];
        $this->putSession('filter', 'all');
    }
}
