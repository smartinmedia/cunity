<?php

/*
 * Cunity - Your private social network 
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
abstract class Table extends \Zend_Db_Table_Abstract {

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
    protected function _setupTableName() {
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
    public function insert(array $data) {
        if (in_array("time", $this->info(\Zend_Db_Table_Abstract::COLS)))
            $data['time'] = new \Zend_Db_Expr("UTC_TIMESTAMP()");
        return parent::insert($data);
    }

    /**
     * @param array $data
     * @param String $where
     * @return int
     */
    public function update(array $data, $where) {
        if (in_array("time", $this->info(\Zend_Db_Table_Abstract::COLS)))
            $data['time'] = new \Zend_Db_Expr("UTC_TIMESTAMP()");
        return parent::update($data, $where);
    }

}
