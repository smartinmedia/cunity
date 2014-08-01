<?php

namespace Notifications\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Notification_Settings
 * @package Notifications\Models\Db\Table
 */
class Notification_Settings extends Table {

    /**
     * @var string
     */
    protected $_name = 'notification_settings';

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $name
     * @param $userid
     * @return int|string
     */
    public function getSetting($name, $userid) {
        $res = $this->fetchRow($this->select()->from($this, "value")->where("userid=?", $userid)->where("name=?", $name));
        if ($res == NULL || $res == false)
            return 3;
        return $res->value;
    }

    /**
     * @param $userid
     * @return int|string
     */
    public function getSettings($userid = null)
    {
        if (null === $userid)
        {
            $userid = $_SESSION['user']->userid;
        }

        /** @var $res \Zend_Db_Table_Row */
        $res = $this->fetchAll($this->select()->from($this, ['name', 'value'])->where("userid=?", $userid));

        $returnValue = [];

        foreach ($res->toArray() as $_setting)
        {
            $returnValue[$_setting['name']] = $_setting['value'];
        }

        return $returnValue;
    }

    /**
     * @param array $values
     * @return bool
     */
    public function updateSettings(array $values) {
        $res = [];
        $res[] = (0 < $this->delete($this->getAdapter()->quoteInto("userid=?", $_SESSION['user']->userid)));
        foreach ($values AS $name => $value)
            $res[] = $this->insert(["userid" => $_SESSION['user']->userid, "name" => $name, "value" => $value]);
        return !in_array(false, $res);
    }

}
