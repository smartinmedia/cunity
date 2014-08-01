<?php

namespace Friends\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;
use Core\Models\Db\Table\Users;

/**
 * Class Relationships
 * @package Friends\Models\Db\Table
 */
class Relationships extends Table {

    /**
     * @var string
     */
    protected $_name = 'relations';
    /**
     * @var string
     */
    protected $_primary = 'relation_id';
    /**
     * @var string
     */
    protected $_rowClass = "\Friends\Models\Db\Row\Relation";

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $user
     * @param $secondUser
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getRelation($user, $secondUser) {
        return $this->fetchRow($this->select()->where("sender=$user AND receiver = $secondUser")->orWhere("sender=$secondUser AND receiver = $user"));
    }

    /**
     * @param $user
     * @param $secondUser
     * @return int
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function deleteRelation($user, $secondUser) {
        return $this->getRelation($user, $secondUser)->delete();
    }

    /**
     * @param $user
     * @param $secondUser
     * @param $status
     * @return mixed
     */
    public function addRelation($user, $secondUser, $status) {
        return $this->insert(["sender" => $user, "receiver" => $secondUser, "status" => $status]);
    }

    /**
     * @param $user
     * @param $secondUser
     * @param array $updates
     * @return mixed
     */
    public function updateRelation($user, $secondUser, array $updates) {
        $rel = $this->getRelation($user, $secondUser);
        if ($rel == NULL) {
            return $this->addRelation($user, $secondUser, 0);
        } else
            return $rel->setFromArray($updates)->save();
    }

    /**
     * @param string $status
     * @param int $userid
     * @return array
     */
    public function getFriendList($status = ">1", $userid = 0) {
        if ($userid == 0)
            $userid = $_SESSION['user']->userid;
        else
            $userid = intval($userid);
        if (!is_string($status) && $status == 0) // Only user, who blocked another people is allowed to get this list
            $query = $this->getAdapter()->query("SELECT receiver AS friend FROM " . $this->_dbprefix . "relations WHERE " . $this->getAdapter()->quoteInto("sender=?", $userid) . " AND status = 0");
        else
            $query = $this->getAdapter()->select()
                    ->from($this->_dbprefix . "relations", new \Zend_Db_Expr("(CASE WHEN sender = " . $userid . " THEN receiver WHEN receiver = " . $userid . " THEN sender END) AS friend"))
                    ->where("status " . $status)
                    ->where("sender=? OR receiver = ? ", $userid);
        $res = $this->getAdapter()->fetchAll($query);
        $result = [];
        foreach ($res AS $friend)
            $result[] = $friend['friend'];
        return $result;
    }

    /**
     * @param string $status
     * @param int $userid
     * @return null
     */
    public function getFullFriendList($status = ">1", $userid = 0) {
        $friends = $this->getFriendList($status, $userid);
        if (!empty($friends)) {
            $users = $_SESSION['user']->getTable();
            return $users->getSet($friends, "u.userid", ["u.userid", "u.username", "u.name"], true)->toArray();
        }return null;
    }

    /**
     * @param int $userid
     * @return array
     */
    public function getFriendRequests($userid = 0) {
        if ($userid == 0)
            $userid = $_SESSION['user']->userid;
        $res = $this->fetchAll($this->select()->from($this, ["sender"])->where("receiver=?", $userid)->where("status=1"));
        $result = [];
        foreach ($res AS $friend)
            $result[] = $friend['sender'];
        return $result;
    }

    /**
     * @param int $userid
     * @return array|null
     */
    public function getFullFriendRequests($userid = 0) {
        $friends = $this->getFriendRequests($userid);
        if (!empty($friends)) {
            $users = new Users();
            return $users->getSet($friends, "u.userid", ["u.userid", "u.username", "u.name"])->toArray();
        }return null;
    }

    /**
     * @param $userid
     * @return array
     */
    public function loadOnlineFriends($userid) {
        return $this->getAdapter()->fetchAll($this->getAdapter()->select()->from(["u" => $this->_dbprefix . "users"], ["userid", "name", "username", "onlineStatus", "chat_available", "(lastAction BETWEEN DATE_SUB(UTC_TIMESTAMP() , INTERVAL 1 MINUTE) AND UTC_TIMESTAMP()) AS online"])
                                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id = u.profileImage", "filename AS pimg")
                                ->where("u.userid IN (" . new \Zend_Db_Expr($this->getAdapter()->select()
                                        ->from($this->_dbprefix . "relations", new \Zend_Db_Expr("(CASE WHEN sender = " . $userid . " THEN receiver WHEN receiver = " . $userid . " THEN sender END)"))
                                        ->where("status > 1")
                                        ->where("sender=? OR receiver = ? ", $userid)) . ")")
                                ->order("u.name DESC")
                        //   ->where(new \Zend_Db_Expr("u.lastAction BETWEEN DATE_SUB(UTC_TIMESTAMP() , INTERVAL 1 MINUTE) AND UTC_TIMESTAMP()"))                                
        );
    }

}
