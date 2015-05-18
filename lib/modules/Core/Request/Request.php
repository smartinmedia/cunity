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
     * @param null|mixed $returnValue
     *
     * @return mixed|null
     */
    public static function get($parameter = null, $returnValue = null)
    {
        $array = static::$requestArray;
        global ${$array};

        if ($parameter === null || !is_array(${$array})) {
            if (!is_array(${$array}) || count(${$array}) === 0) {
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

    /**
     * @return bool
     */
    public static function hasAction()
    {
        $array = static::$requestArray;
        global ${$array};

        return (array_key_exists('action', ${$array}) && ${$array}['action'] !== '');
    }
}
