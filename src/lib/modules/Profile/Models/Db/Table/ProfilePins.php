<?php

namespace Profile\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class ProfilePins
 * @package Profile\Models\Db\Table
 */
class ProfilePins extends Table {

    /**
     * @var string
     */
    protected $_name = 'profile_pins';
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
     * @param $userid
     * @return array|\Zend_Db_Table_Rowset_Abstract
     */
    public function getAllByUser($userid) {
        $pt = new Privacy();
        $res = $pt->checkPrivacy("visit",$userid);                        
        if ($res)            
            return $this->fetchAll($this->select()->where("userid=?", $userid)->order("row"));
        else
            return ["status" => true];
    }

    /**
     * @param $columns
     * @param $row
     * @param $pinid
     * @return int
     */
    public function updatePosition($columns, $row, $pinid) {
        return $this->update(["column" => $columns, "row" => $row], $this->getAdapter()->quoteInto("id=?", $pinid));
    }

}
