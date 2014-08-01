<?php

namespace Core;

use Core\Models\Db\Table\Settings;
use Core\Exception;
use Core\Models\Db\Adapter\Mysqli;
use Zend_Db_Table_Abstract;

/**
 * Class Cunity
 * @package Core
 */
class Cunity {

    /**
     * @var Cunity
     */
    private static $_instance = null;

    /**
     * @var array
     */
    private static $_instances = [];

    /**
     * @throws Core\Exception
     */
    public static function init() {
        self::set("config", new \Zend_Config_Xml("../data/config.xml"));
        self::set(
                "db", new Mysqli(self::get("config"))
        );
        Zend_Db_Table_Abstract::setDefaultAdapter(self::get("db"));
        self::set("settings", new Settings());
        if (function_exists("apache_get_modules")) {
            self::set(
                    "mod_rewrite", in_array(
                            'mod_rewrite', apache_get_modules()
                    )
            );
        } else {
            self::set("mod_rewrite", false);
        }
    }

    /**
     * @param String $instance
     * @param mixed $obj
     */
    public static function set($instance, $obj) {
        self::$_instances[$instance] = $obj;
    }

    /**
     * @param String $instance
     * @throws Core\Exception
     * @return mixed
     */
    public static function get($instance) {
        if (isset(self::$_instances[$instance])) {
            return self::$_instances[$instance];
        } else {
            throw new Exception(
            "Instance of \"" . $instance . "\" not found!"
            );
        }
    }

}
