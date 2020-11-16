<?php namespace R4L\Localize\Widgets;

class LanguageList extends \RainLab\Builder\Widgets\LanguageList
{
    public function __construct($controller, $alias)
    {
        parent::__construct($controller, $alias);

        $this->viewPath = [
            $this->viewPath,
            '$/rainlab/builder/widgets/languagelist/partials',
        ];
    }
}
