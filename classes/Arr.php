<?php namespace StudioAzura\Localize\Classes;

use Illuminate\Support\Arr as ArrHelper;
		
if (!function_exists('array_undot')) {
	/**
	 * Transform a dot-notated array into a normal array.
	 *
	 * @param array $dotArray
	 * @return array
	 */
	function array_undot(array $dotArray)
	{
		return \StudioAzura\Localize\Classes\Arr::undot($dotArray);
	}
}

class Arr extends ArrHelper
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
