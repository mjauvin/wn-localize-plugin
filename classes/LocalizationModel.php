<?php namespace R4L\Localize\Classes;

use ApplicationException;
use Symfony\Component\Yaml\Dumper as YamlDumper;
use SystemException;
use DirectoryIterator;
use ValidationException;
use Yaml;
use Exception;
use Config;
use Lang;
use File;

class LocalizationModel extends \RainLab\Builder\Classes\LocalizationModel
{
    public function getOverrideFilePath($language = null)
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

        $path = '~/lang/'.$language.'/'.$this->getPluginCodeObj()->toFilesystemPath().'/lang.php';

        return File::symbolizePath($path);
    }

    public function load($language)
    {
        $this->language = $language;

        $this->originalLanguage = $language;

        $overrideFilePath = $this->getOverrideFilePath();
        $filePath = $this->getFilePath();

        if (!File::isFile($filePath) && !File::isFile($overrideFilePath)) {
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
        $isNew = $this->isNewModel();

        if (File::isFile($filePath)) {
            if (!$data) {
                File::delete($filePath);
                $this->exists = true;
                return;
            }
        }

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

    protected function modelToLanguageFile()
    {
        $this->strings = trim($this->strings);

        if (!strlen($this->strings)) {
            return null;
        }

        try {
            $updates = $this->getSanitizedPHPStrings(Yaml::parse($this->strings));
            $changes = array_diff_assoc(array_dot($updates), array_dot($this->originalStringArray));
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
        $result = parent::listPluginLanguages($pluginCodeObj);

        $overrideLanguagesDirectoryPath = File::symbolizePath('~/lang');

        if (!File::isDirectory($overrideLanguagesDirectoryPath)) {
            return $result;
        }

        $pluginPath = '/'.$pluginCodeObj->toFilesystemPath();

        foreach (new DirectoryIterator($overrideLanguagesDirectoryPath) as $fileInfo) {
            if (!$fileInfo->isDir() || $fileInfo->isDot()) {
                continue;
            }

            $langFilePath = $fileInfo->getPathname().$pluginPath.'/lang.php';

            $lang = $fileInfo->getFilename();
            if (File::isFile($langFilePath) && !in_array($lang, $result)) {
                $result[] = $lang;
            }
        }

        return $result;
    }
}
