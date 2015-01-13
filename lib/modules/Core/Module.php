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

namespace Cunity\Core;

use Cunity\Core\Models\Db\Table\Modules;
use Cunity\Core\View\PageNotFound;

/**
 * Class Module
 * @package Core
 */
class Module
{
    /**
     * @var array
     */
    private static $FIXMODULES = ['admin', 'start', 'register', 'notifications', 'comments', 'search'];

    /**
     * @var
     */
    protected $_tag;
    /**
     * @var \Zend_Db_Table_Row_Abstract
     */
    private $_data;

    /**
     * @param $moduletag
     */
    public function __construct($moduletag)
    {
        $this->_tag = $moduletag;
        if (!class_exists($this->getClassName())) {
            new View\PageNotFound;

        } else {
            $modules = new Modules();
            $this->_data = $modules->getModuleData($this->_tag);

            if (!in_array($moduletag, self::$FIXMODULES) &&
                $this->_data === null
            ) {
                new PageNotFound();
            }
        }
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return "Cunity\\" . ucfirst($this->_tag) . "\Controller";
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (class_exists($this->getClassName()) &&
            in_array(
                'Cunity\Core\ModuleController',
                class_parents($this->getClassName())
            )
        );
    }

    /**
     * @return bool|string
     */
    public function isActive()
    {
        if ($this->_data !== NULL)
            return $this->_data['status'];
        else
            return true;
    }
}
