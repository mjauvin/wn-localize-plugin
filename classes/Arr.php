<?php namespace StudioAzura\Localize\Classes;

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
}

class Arr extends October\Rain\Support
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
