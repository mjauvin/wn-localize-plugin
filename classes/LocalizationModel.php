<?php namespace StudioAzura\Localize\Classes;

use AppendIterator;
use ApplicationException;
use Config;
use DirectoryIterator;
use Exception;
use File;
use Lang;
use RainLab\Builder\Classes\LanguageMixer;
use Symfony\Component\Yaml\Dumper as YamlDumper;
use SystemException;
use ValidationException;
use Yaml;

class LocalizationModel extends \RainLab\Builder\Classes\LocalizationModel
{
    public $files;

    public $languageFile = null;

    protected function purgeEmptyArrays(&$array) {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                if (empty($value)) {
                    $value = null;
                } else {
                    $this->purgeEmptyArrays($value);
                }
            }
        }
    }

    protected function getOverrideLangPath($language)
    {
        return File::symbolizePath('~/lang/' . $language . '/' . $this->getPluginCodeObj()->toFilesystemPath());
    }

    protected function getOverrideFilePath($language = null)
    {
        if ($language === null) {
            $language = $this->language;
        }

        $language = trim($language);

        if (!strlen($language)) {
            throw new SystemException('The form model language is not set.');
        }

        if (!$this->validateLanguage($language)) {
            throw new SystemException('Invalid language file name: '.$language);
        }

        return $this->getOverrideLangPath($language) . '/' . $this->languageFile;
    }

    protected function getLangPath($language)
    {
        return File::symbolizePath($this->getPluginCodeObj()->toPluginDirectoryPath().'/lang/'.$language);
    }

    protected function getFilePath($language = null)
    {
        if ($language === null) {
            $language = $this->language;
        }

        $language = trim($language);

        if (!strlen($language)) {
            throw new SystemException('The form model language is not set.');
        }

        if (!$this->validateLanguage($language)) {
            throw new SystemException('Invalid language file name: '.$language);
        }

        return $this->getLangPath($language) . '/' . $this->languageFile;
    }

    public function getFilesOptions()
    {
        $pluginLangPath = $this->getLangPath($this->language);
        $overrideLangPath = $this->getOverrideLangPath($this->language);

        $directories = [];
        if (File::isDirectory($pluginLangPath)) {
            $directories[] = $pluginLangPath;
        }
        if (File::isDirectory($overrideLangPath)) {
            $directories[] = $overrideLangPath;
        }

        $files = [];
        foreach ($directories as $dir) {
            $files = array_merge($files, File::files($dir));
        }

        return array_unique(array_map('basename', $files));
    }

    public function load($language)
    {
        $this->language = $this->originalLanguage = $language;

        $overrideFilePath = $this->getOverrideFilePath();
        $filePath = $this->getFilePath();

        if ($this->languageFile && !File::isFile($filePath) && !File::isFile($overrideFilePath)) {
            throw new ApplicationException(Lang::get('rainlab.builder::lang.localization.error_cant_load_file'));
        }

        if (File::isFile($filePath)) {
            if (!$this->validateFileContents($filePath)) {
                throw new ApplicationException(Lang::get('rainlab.builder::lang.localization.error_bad_localization_file_contents'));
            }
            $strings = include($filePath);
            if (!is_array($strings)) {
                throw new ApplicationException(Lang::get('rainlab.builder::lang.localization.error_file_not_array'));
            }

        } else {
            $strings = [];
        }

        $this->purgeEmptyArrays($strings);

        $this->originalStringArray = $strings;

        if (File::isFile($overrideFilePath)) {
            $overrideStrings = include($overrideFilePath);
            if (is_array($overrideStrings)) {
                $strings = array_merge($strings, $overrideStrings);
            }
        }

        if (count($strings) > 0) {
            $dumper = new YamlDumper();
            $this->strings = $dumper->dump($strings, 20, 0, false, true);
        }
        else {
            $this->strings = '';
        }

        $this->exists = true;
    }

    public function save()
    {
        $data = $this->modelToLanguageFile();
        $this->validate();
        $filePath = $this->getOverrideFilePath();

        if (!$data) {
            if (File::isFile($filePath)) {
                File::delete($filePath);
            }
            $this->exists = false;
            return;
        }

        $isNew = $this->isNewModel();


        $fileDirectory = dirname($filePath);
        if (!File::isDirectory($fileDirectory)) {
            if (!File::makeDirectory($fileDirectory, 0777, true, true)) {
                throw new ApplicationException(Lang::get('rainlab.builder::lang.common.error_make_dir', ['name'=>$fileDirectory]));
            }
        }

        if (@File::put($filePath, $data) === false) {
            throw new ApplicationException(Lang::get('rainlab.builder::lang.localization.save_error', ['name'=>$filePath]));
        }

        @File::chmod($filePath);

        $this->originalLanguage = $this->language;
        $this->exists = true;
    }

    public function initContent()
    {
    }

    public function deleteModel()
    {
        if ($this->isNewModel()) {
            throw new ApplicationException('Cannot delete language file which is not saved yet.');
        }

        $filePath = $this->getOverrideFilePath();
        if (File::isFile($filePath)) {
            if (!@File::delete($filePath)) {
                throw new ApplicationException(Lang::get('rainlab.builder::lang.localization.error_delete_file'));
            }
        }
    }

    public function copyStringsFrom($destinationText, $sourceLanguageCode)
    {
        $sourceLanguageModel = new self();
        $sourceLanguageModel->languageFile = $this->languageFile;
        $sourceLanguageModel->setPluginCodeObj($this->getPluginCodeObj());
        $sourceLanguageModel->load($sourceLanguageCode);

        $srcArray = $sourceLanguageModel->getOriginalStringsArray();

        $languageMixer = new LanguageMixer();

        return $languageMixer->addStringsFromAnotherLanguage($destinationText, $srcArray);
    }

    protected function modelToLanguageFile()
    {
        $this->strings = trim($this->strings);

        if (!strlen($this->strings)) {
            return null;
        }

        try {
            $updates = $this->getSanitizedPHPStrings(Yaml::parse($this->strings));
            $this->purgeEmptyArrays($updates);

            $updatesDotted = array_dot($updates);
            $originalDotted = array_dot($this->originalStringArray);

            $changes = array_diff_assoc($updatesDotted, $originalDotted);
            $data = array_undot($changes);
            if (empty($data)) {
                return null;
            }

            $phpData = var_export($data, true);
            $phpData = preg_replace('/^(\s+)\),/m', '$1],', $phpData);
            $phpData = preg_replace('/^(\s+)array\s+\(/m', '$1[', $phpData);
            $phpData = preg_replace_callback('/^(\s+)/m', function ($matches) {
                return str_repeat($matches[1], 2); // Increase indentation
            }, $phpData);
            $phpData = preg_replace('/\n\s+\[/m', '[', $phpData);
            $phpData = preg_replace('/^array\s\(/', '[', $phpData);
            $phpData = preg_replace('/^\)\Z/m', ']', $phpData);

            return "<?php return ".$phpData.";";
        }
        catch (Exception $ex) {
            throw new ApplicationException(sprintf('Cannot parse the YAML content: %s', $ex->getMessage()));
        }
    }

    public static function listPluginLanguages($pluginCodeObj)
    {
        $result = [];
        $pluginPath = '/'.$pluginCodeObj->toFilesystemPath();
        $languagesDirectoryPath = File::symbolizePath($pluginCodeObj->toPluginDirectoryPath().'/lang');
        $overrideLanguagesDirectoryPath = File::symbolizePath('~/lang');

        $directories = new AppendIterator();
        if (File::isDirectory($languagesDirectoryPath)) {
            $directories->append(new DirectoryIterator($languagesDirectoryPath));
        }
        if (File::isDirectory($overrideLanguagesDirectoryPath)) {
            $directories->append(new DirectoryIterator($overrideLanguagesDirectoryPath));
        }

        foreach ($directories as $fileInfo) {
            if (!$fileInfo->isDir() || $fileInfo->isDot()) {
                continue;
            }

            $files = File::files($fileInfo->getPathname());
            $langFilesPath = $fileInfo->getPathname() . '/' . $pluginPath;

            if (File::isDirectory($langFilesPath)) {
                $files = array_merge($files, File::files($langFilesPath));
            }

            $lang = $fileInfo->getFilename();
            if ($files && !in_array($lang, $result)) {
                $result[] = $lang;
            }
        }

        sort($result);
        return $result;
    }
}

if (!function_exists('array_undot')) {
    /**
     * Transform a dot-notated array into a normal array.
     *
     * @param array $dotArray
     * @return array
     */
    function array_undot(array $dotArray)
    {
        return Arr::undot($dotArray);
    }

    class Arr extends \Illuminate\Support\Arr
    {
        public static function undot(array $dotArray)
        {
            $array = [];

            foreach ($dotArray as $key => $value) {
                static::set($array, $key, $value);
            }

            return $array;
        }
    }
}
