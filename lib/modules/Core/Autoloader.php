<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################
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

namespace Core;

/**
 * Class Autoloader
 * @package Core
 */
class Autoloader
{

    /**
     * @var array
     */
    private $_namespaces = ["Zend"];

    /**
     * @param array $namespaces
     */
    public function __construct(array $namespaces = ["Zend"])
    {
        spl_autoload_register([$this, "load"]);
        $this->_namespaces = $namespaces;
    }

    /**
     * @param $namespace
     */
    public function addAutoloadNamespace($namespace)
    {
        if (!in_array($namespace, $this->_namespaces)){
            $this->_namespaces[] = $namespace;
        }
    }

    /**
     * @param $name
     */
    public function load($name)
    {
        if (preg_match('/' . implode('|',$this->_namespaces). '/',
                $name
            ) == 1
        ) {
            $name = str_replace("_", "/", $name);
            require_once $name . ".php";
        } else if (strpos($name, "\\") !== false) {
            $name = str_replace("\\", "/", $name);
            require_once "./modules/" . $name . ".php";
        }
    }

}
