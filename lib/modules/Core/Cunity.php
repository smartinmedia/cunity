<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * 1. YOU MUST NOT CHANGE THE LICENSE FOR THE SOFTWARE OR ANY PARTS HEREOF! IT MUST REMAIN AGPL.
 * 2. YOU MUST NOT REMOVE THIS COPYRIGHT NOTES FROM ANY PARTS OF THIS SOFTWARE!
 * 3. NOTE THAT THIS SOFTWARE CONTAINS THIRD-PARTY-SOLUTIONS THAT MAY EVENTUALLY NOT FALL UNDER (A)GPL!
 * 4. PLEASE READ THE LICENSE OF THE CUNITY SOFTWARE CAREFULLY!
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program (under the folder LICENSE).
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * If your software can interact with users remotely through a computer network,
 * you have to make sure that it provides a way for users to get its source.
 * For example, if your program is a web application, its interface could display
 * a "Source" link that leads users to an archive of the code. There are many ways
 * you could offer source, and different solutions will be better for different programs;
 * see section 13 of the GNU Affero General Public License for the specific requirements.
 *
 * #####################################################################################
 */

namespace Cunity\Core;

use Cunity\Core\Exceptions\Exception;
use Cunity\Core\Exceptions\InstanceNotFound;
use Cunity\Core\Models\Db\Adapter\Mysqli;
use Cunity\Core\Models\Db\Table\Settings;
use Zend_Db_Table_Abstract;

/**
 * Class Cunity.
 */
class Cunity
{
    /**
     * @var array
     */
    private static $_instances = [];

    /**
     * @throws Exception
     */
    public static function init()
    {
        self::set('config', new \Zend_Config_Xml(__DIR__.'/../../../data/config.xml'));
        self::set(
            'db',
            new Mysqli(self::get('config'))
        );
        Zend_Db_Table_Abstract::setDefaultAdapter(self::get('db'));
        self::set('settings', new Settings());
        if (function_exists('apache_get_modules')) {
            self::set(
                'mod_rewrite', in_array(
                    'mod_rewrite', apache_get_modules()
                )
            );
        } else {
            self::set('mod_rewrite', false);
        }
    }

    /**
     * @param String $instance
     * @param mixed  $obj
     */
    public static function set($instance, $obj)
    {
        self::$_instances[$instance] = $obj;
    }

    /**
     * @param $instance
     *
     * @return mixed
     *
     * @throws Exception
     */
    public static function get($instance)
    {
        if (isset(self::$_instances[$instance])) {
            return self::$_instances[$instance];
        } else {
            throw new InstanceNotFound;
        }
    }
}
