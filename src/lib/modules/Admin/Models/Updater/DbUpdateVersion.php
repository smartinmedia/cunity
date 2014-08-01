<?php

namespace Admin\Models\Updater;

use Admin\Models\Db\Table\Versions;

/**
 * Class DbUpdateVersion
 * @package Admin\Models\Updater
 * @abstract
 */
abstract class DbUpdateVersion {

    /**
     *
     * @var \Zend_Db_Adapter_Mysqli
     */
    protected $_db = null;

    /**
     *
     * @var long 
     */
    protected $_timestamp = 0;

    /**
     * 
     * @param \Zend_Db_Adapter_Mysqli $database
     */
    public function __construct(\Zend_Db_Adapter_Mysqli $database) {
        $this->_db = $database;
    }

    /**
     * 
     */
    public function updateDatabaseTimestamp(Versions $db) {
        $db->insert(["timestamp"=>$this->_timestamp]);
    }

}
