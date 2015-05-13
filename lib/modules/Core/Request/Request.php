<?php

namespace Cunity\Core\Request;

/**
 * Interface Request.
 */
abstract class Request
{
    /**
     * @var string
     */
    protected static $requestArray = '_REQUEST';

    /**
     * @param null $parameter
     * @param null $returnValue
     *
     * @return null
     */
    public static function get($parameter = null, $returnValue = null)
    {
        $array = static::$requestArray;
        global ${$array};

        if ($parameter === null) {
            if (count(${$array}) === 0) {
                return $returnValue;
            } else {
                return ${$array};
            }
        }

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
