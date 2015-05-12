<?php

namespace Cunity\Core\Traits;

/**
 * Class Singleton.
 */
trait Singleton
{
    /**
     * @var array
     */
    private static $instances = [];

    /**
     *
     */
    private function __construct()
    {
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        return self::prepareInstance();
    }

    /**
     * @return mixed
     */
    protected static function prepareInstance()
    {
        $classname = get_called_class();

        self::createInstance($classname);

        return self::$instances[$classname];
    }

    /**
     * @param $classname
     */
    protected static function createInstance($classname)
    {
        if (!array_key_exists($classname, self::$instances)) {
            self::$instances[$classname] = new $classname();
        }
    }

    /**
     *
     */
    private function __clone()
    {
    }
}
