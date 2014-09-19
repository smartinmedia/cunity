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

namespace Core\Models\Db\Abstractables;

use Core\Cunity;

/**
 * abstract Table Class which automatically inserts the database-prefix
 *
 * @package    Core
 * @subpackage Abstractables
 * @copyright  Smart In Media GmbH & Co. KG (www.smartinmedia.com)
 */
abstract class Table extends \Zend_Db_Table_Abstract
{

    /**
     * Stores the config-object
     *
     * @var \Zend_Config_Xml
     */
    protected $_config;

    /**
     * Stores the Table Prefix as a shortcut variable
     *
     * @var String
     */
    protected $_dbprefix;

    /**
     * Overwrite the default Rowset-Class
     *
     * @var String
     */
    protected $_rowsetClass = "Core\Models\Db\Rowset\Rowset";

    /**
     * @throws \Exception
     */
    protected function _setupTableName()
    {
        $this->_config = Cunity::get("config");
        $this->_dbprefix = $this->_config->db->params->table_prefix . '_';
        $this->_name = $this->_dbprefix . $this->_name;
        parent::_setupTableName();
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Zend_Db_Table_Exception
     */
    public function insert(array $data)
    {
        if (in_array("time", $this->info(\Zend_Db_Table_Abstract::COLS)))
            $data['time'] = new \Zend_Db_Expr("UTC_TIMESTAMP()");
        return parent::insert($data);
    }

    /**
     * @param array $data
     * @param String $where
     * @return int
     */
    public function update(array $data, $where)
    {
        if (in_array("time", $this->info(\Zend_Db_Table_Abstract::COLS)))
            $data['time'] = new \Zend_Db_Expr("UTC_TIMESTAMP()");
        return parent::update($data, $where);
    }

}
