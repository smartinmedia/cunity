<?php

namespace Cunity\Core\Request;

/**
 * Interface Request
 * @package Cunity\Core\Request
 */
abstract class Request
{
    /**
     * @var string
     */
    protected static $requestArray = '_REQUEST';

    /**
     * @param $parameter
     * @return mixed
     */
    public static function get($parameter)
    {
        $array = static::$requestArray;
        global ${$array};

        $returnValue = null;

        if (array_key_exists($parameter, ${$array})) {
            $returnValue = ${$array}[$parameter];
        }

        return $returnValue;
    }

    /**
     * @param $parameter
     * @param $value
     */
    public static function set($parameter, $value)
    {
        $array = static::$requestArray;
        global ${$array};

        ${$array}[$parameter] = $value;
    }
}
