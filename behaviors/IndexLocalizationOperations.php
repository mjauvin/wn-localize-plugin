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
    protected $baseFormConfigFile = '$/r4l/localize/classes/localizationmodel/fields.yaml';

    public function __construct($controller)
    {
        parent::__construct($controller); 
        $this->viewPath = [
            $this->viewPath,
            '$/rainlab/builder/behaviors/indexlocalizationoperations/partials',
        ];
    }

    public function onLanguageCreateOrOpen()
    {
        $language = Input::get('original_language');
        $pluginCodeObj = $this->getPluginCode();

        $options = [
            'pluginCode' => $pluginCodeObj->toCode()
        ];

        $widget = $this->makeBaseFormWidget($language, $options);

        $this->vars['originalLanguage'] = $language;
        $this->vars['languageFile'] = 'lang.php';

        if ($widget->model->isNewModel()) {
            $widget->model->initContent();
        }

        $result = [
            'tabTitle' => $this->getTabName($widget->model),
            'tabIcon' => 'icon-globe',
            'tabId' => $this->getTabId($pluginCodeObj->toCode(), $language),
            'isNewRecord' => $widget->model->isNewModel(),
            'tab' => $this->makePartial('tab', [
                'form'  => $widget,
                'pluginCode' => $pluginCodeObj->toCode(),
                'language' => $language,
                'defaultLanguage' => LocalizationModel::getDefaultLanguage()
            ])
        ];

        return $result;
    }

    public function onLanguageGetStrings()
    {
        $model = $this->loadOrCreateLocalizationFromPost();

        return ['builderResponseData' => [
            'strings' => $model ? $model->strings : null
        ]];
    }

    protected function loadOrCreateLocalizationFromPost()
    {
        $pluginCodeObj = new PluginCode(Request::input('plugin_code'));
        $languageFile = Input::get('language_file', 'lang.php');
        $options = [
            'pluginCode' => $pluginCodeObj->toCode(),
            'languageFile' => $languageFile,
        ];

        $originalLanguage = Input::get('original_language');

        return $this->loadOrCreateBaseModel($originalLanguage, $options);
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

        $model->languageFile = array_get($options, 'languageFile', 'lang.php');
        $model->load($language);
        return $model;
    }
}
