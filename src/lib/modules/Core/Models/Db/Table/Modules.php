<?php

namespace Core\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Modules
 * @package Core\Models\Db\Table
 */
class Modules extends Table {

    /**
     * @var string
     */
    protected $_name = 'modules';
    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function getModules() {
        return $this->fetchAll();
    }

    /**
     * @param $moduletag
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getModuleData($moduletag) {
        return $this->fetchRow($this->select()->where("namespace=?",$moduletag)->limit(1));
    }

}
