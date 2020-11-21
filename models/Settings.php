<?php namespace StudioAzura\Localize\Models;

use File;
use Model;

class Settings extends Model
{
    use \System\Traits\ViewMaker;

    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'studioazura_localize_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
