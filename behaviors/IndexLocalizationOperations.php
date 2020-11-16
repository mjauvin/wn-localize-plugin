<?php namespace R4L\Localize\Behaviors;

use R4L\Localize\Classes\LocalizationModel;

use RainLab\Builder\Classes\PluginCode;
use ApplicationException;
use Exception;
use Request;
use Flash;
use Input;
use Lang;

/**
 * Plugin localization management functionality for the Builder index controller
 *
 * @package rainlab\builder
 * @author Alexey Bobkov, Samuel Georges
 */
class IndexLocalizationOperations extends \RainLab\Builder\Behaviors\IndexLocalizationOperations
{
    public function __construct($controller)
    {
        parent::__construct($controller); 
        $this->viewPath = [
            $this->viewPath,
            '$/rainlab/builder/behaviors/indexlocalizationoperations/partials',
        ];
    }

    protected function loadOrCreateBaseModel($language, $options = [])
    {
        $model = new LocalizationModel();

        if (isset($options['pluginCode'])) {
            $model->setPluginCode($options['pluginCode']);
        }

        if (!$language) {
            return $model;
        }

        $model->load($language);
        return $model;
    }
}
