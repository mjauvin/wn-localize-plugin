<?php namespace StudioAzura\Localize\Console;

use File;
use Lang;
use ApplicationException;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Console command to add missing language translation keys for a target locale
 *
 * @package winter\wn-system-module
 * @author Marc Jauvin
 * @author Winter CMS
 */
class WinterLang extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'winter:lang';

    /**
     * @var string The console command description.
     */
    protected $description = 'Add missing language translation keys for target locale';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = $this->argument('module')
            ?? $this->ask("Provide a module ['cms', 'backend', 'system']");

        $modulePath = base_path('modules/' . $module);
        if (!File::isDirectory($modulePath)) {
            throw new InvalidArgumentException('The module does not exists: ' . $modulePath);
        }

        $langFile = $this->argument('langFile')
            ?? $this->ask('Provide a language file');

        $langFile = basename($langFile, '.php') . '.php';

        $langFilePath = $modulePath . '/lang/en/' . $langFile;
        if (!File::exists($langFilePath)) {
            throw new InvalidArgumentException('The language file does not exist: ' . $langFilePath);
        }
        $strings = $result = include($langFilePath);

        $targetLocale = $this->argument('targetLocale')
            ?? $this->ask('For which language do you want to generate missing keys');

        $targetLangFilePath = $modulePath . '/lang/' . $targetLocale . '/' . $langFile;

        if (File::exists($targetLangFilePath)) {
            $targetStrings = include($targetLangFilePath);
            $result = array_merge($strings, $targetStrings);
        }

        $outputFile = sprintf('%s/%s/%s/%s', base_path('lang'), $targetLocale, $module, $langFile);
        $outputDirectory = dirname($outputFile);
        if (!File::isDirectory($outputDirectory)) {
            if (!File::makeDirectory($outputDirectory, 0777, true, true)) {
                throw new ApplicationException(Lang::get('rainlab.builder::lang.common.error_make_dir', ['name'=>$outputDirectory]));
            }
        }

        if (@File::put($outputFile, $this->dumpStrings($result)) === false) {
            throw new ApplicationException(Lang::get('rainlab.builder::lang.localization.save_error', ['name'=>$filePath]));
        }

        @File::chmod($outputFile);

        $this->info("The merged localization file has been saved in your Application lang folder: \n\n" . $outputFile);

        exit(0);
    }

    protected function dumpStrings($stringsArray)
    {
        if (count($stringsArray) === 0) {
            return "<?php return [\n];";
        }

        try {
            $phpData = var_export($this->getSanitizedPHPStrings($stringsArray), true);

            $phpData = preg_replace('/^(\s+)\),/m', '$1],', $phpData);
            $phpData = preg_replace('/^(\s+)array\s+\(/m', '$1[', $phpData);

            $phpData = preg_replace_callback('/^(\s+)/m', function ($matches) {
                return str_repeat($matches[1], 2); // Increase indentation
            }, $phpData);

            $phpData = preg_replace('/\n\s+\[/m', '[', $phpData);
            $phpData = preg_replace('/^array\s\(/', '[', $phpData);
            $phpData = preg_replace('/^\)\Z/m', ']', $phpData);

            // safely remove single quotes around added double quotes
            $phpData = preg_replace('/\'"/', '"', $phpData);
            $phpData = preg_replace('/"\'/', '"', $phpData);

            // remove escaped single quotes because they have been double quoted
            $phpData = preg_replace("/\\\'/", "'", $phpData);

            return "<?php return ".$phpData.";";
        }
        catch (Exception $ex) {
            throw new ApplicationException(sprintf('Cannot parse the YAML content: %s', $ex->getMessage()));
        }
    }

    protected function getSanitizedPHPStrings($strings)
    {
        array_walk_recursive($strings, function (&$item, $key) {
            if (!is_scalar($item)) {
                return;
            }

            $unquotedItem = trim($item, "'");
            // replace single quotes around the string if single quotes are found inside.
            if (strpos($unquotedItem, "'") !== false) {
                $item = '"' . $unquotedItem . '"';
            }
        });

        return $strings;
    }

    /**
     * Get the console command options.
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The module for the language file'],
            ['langFile', InputArgument::OPTIONAL, 'The language file to work on'],
            ['targetLocale', InputArgument::OPTIONAL, 'The target locale for which to add missing keys'],
        ];
    }
}
