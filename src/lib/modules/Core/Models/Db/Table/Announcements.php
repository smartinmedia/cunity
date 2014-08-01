<?php

namespace Core\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Settings
 * @package Core\Models\Db\Table
 */
class Announcements extends Table {

    /**
     * @var string
     */
    protected $_name = 'announcements';

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     * @return array
     */
    public function getAnnouncements() {
        $res = $this->fetchAll($this->select()->where("active = 1")->order("time DESC"));
        if ($res !== NULL)
            return $res->toArray();
        return [];
    }

}
