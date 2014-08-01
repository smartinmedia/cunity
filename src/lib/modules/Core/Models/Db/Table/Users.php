<?php

namespace Core\Models\Db\Table;

use Core\Cunity;
use Core\Models\Db\Abstractables\Table;
use Core\Models\Generator\Privacy;
use Core\Models\Generator\Unique;
use Register\View\VerifyMail;

/**
 * Class Users
 * @package Core\Models\Db\Table
 */
class Users extends Table {

    /**
     * @var string
     */
    protected $_name = 'users';
    /**
     * @var string
     */
    protected $_primary = 'userid';
    /**
     * @var string
     */
    protected $_rowClass = "\Core\Models\Db\Row\User";

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function registerNewUser(array $data) {
        $salt = Unique::createSalt(25);

        if (Cunity::get("settings")->getSetting("core.fullname")) {
            $name = ($data['firstname'] . " " . $data['lastname']);
        } else {
            $name = ($data['username']);
        }

        $result = $this->insert([
            "email" => trim($data['email']),
            "userhash" => $this->createUniqueHash(),
            "username" => $data['username'],
            "groupid" => 0,
            "password" => sha1(trim($data['password']) . $salt),
            "salt" => $salt,
            "name" => $name,
            "firstname" => $data['firstname'],
            "lastname" => $data['lastname'],
            "sex" => $data['sex']
        ]);
        if ($result) {
            new VerifyMail(["name" => $name, "email" => $data['email']], $salt);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    private function createUniqueHash() {
        $str = Unique::createSalt(32);
        if ($this->search("userhash", $str) !== NULL)
            return $this->createUniqueHash();
        else
            return $str;
    }

    /**
     * @param $key
     * @param $value
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function search($key, $value) {
        return $this->fetchRow($this->select()->where($this->getAdapter()->quoteIdentifier($key) . " = ?", $value));
    }

    /**
     * @param $userid
     * @param string $key
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function get($userid, $key = "userid") {
        $res = $this->fetchRow($this->select()->setIntegrityCheck(false)
                        ->from(["u" => $this->_dbprefix . "users"])
                        ->joinLeft(["fr" => $this->_dbprefix . "relations"], "(fr.sender = u.userid OR fr.receiver = u.userid) AND status = 2", new \Zend_Db_Expr("COUNT(DISTINCT fr.relation_id) AS friendscount"))
                        ->joinLeft(["a" => $this->_dbprefix . "gallery_albums"], "u.userid=a.owner_id AND a.owner_type IS NULL AND (((a.privacy = 2 OR (a.privacy = 1 AND a.owner_id IN (" . new \Zend_Db_Expr($this->getAdapter()->select()->from($this->_dbprefix . "relations", new \Zend_Db_Expr("(CASE WHEN sender = " . $_SESSION['user']->userid . " THEN receiver WHEN receiver = " . $_SESSION['user']->userid . " THEN sender END)"))->where("status > 0")->where("sender=?", $_SESSION['user']->userid)->orWhere("receiver=?", $_SESSION['user']->userid)) . "))) AND a.photo_count > 0) OR (a.owner_type IS NULL AND a.owner_id = " . $_SESSION['user']->userid . " ))", new \Zend_Db_Expr("COUNT(DISTINCT a.id) AS albumscount"))
                        ->joinLeft(["p" => $this->_dbprefix . "privacy"], "p.userid=u.userid", new \Zend_Db_Expr("GROUP_CONCAT(CONCAT(p.type,':',p.value)) AS privacy"))
                        ->joinLeft(["r" => $this->_dbprefix . "relations"], "(r.receiver = " . $this->getAdapter()->quote($_SESSION['user']->userid) . " AND r.sender = u.userid) OR (r.sender = " . $this->getAdapter()->quote($_SESSION['user']->userid) . " AND r.receiver = u.userid)")
                        ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id = u.profileImage", ['filename AS pimg', 'albumid AS palbumid'])
                        ->joinLeft(["ti" => $this->_dbprefix . "gallery_images"], "ti.id = u.titleImage", ["filename AS timg", "albumid AS talbumid"])
                        ->where("u." . $key . " = ?", $userid)
        );
        $res->privacy = Privacy::parse($res->privacy);
        return $res;
    }

    /**
     * @param array $userids
     * @param string $key
     * @param array $fields
     * @param bool $includeOwn
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function getSet(array $userids, $key = "u.userid", array $fields = ["*"], $includeOwn = false) {
        $query = $this->select()->setIntegrityCheck(false)->from(["u" => $this->_dbprefix . "users"], $fields)
                ->joinLeft(["r" => $this->_dbprefix . "relations"], "(r.receiver = " . $this->getAdapter()->quote($_SESSION['user']->userid) . " AND r.sender = u.userid) OR (r.sender = " . $this->getAdapter()->quote($_SESSION['user']->userid) . " AND r.receiver = u.userid)")
                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id = u.profileImage", ['filename AS pimg', 'albumid AS palbumid'])
                ->joinLeft(["ti" => $this->_dbprefix . "gallery_images"], "ti.id = u.titleImage", ["filename AS timg", "albumid AS talbumid"])
                ->joinLeft(["p" => $this->_dbprefix . "privacy"], "p.userid=u.userid", new \Zend_Db_Expr("GROUP_CONCAT(CONCAT(p.type,':',p.value)) AS privacy"))
                ->group("u.userid")
                ->where("u.groupid > 0");
        if (!$includeOwn)
            $query->where("u.userid != ?", $_SESSION['user']->userid);
        if (!empty($userids))
            $query->where($key . " IN(?)", $userids);
        // echo $query->__toString();
        $res = $this->fetchAll($query);
        for ($i = 0; $i < count($res); $i++)
            $res[$i]->privacy = Privacy::parse($res[$i]->privacy);

        return $res;
    }

    /**
     * @param array $userids
     * @param array $in
     * @param string $key
     * @param string $keyIn
     * @param array $fields
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function getSetIn(array $userids, array $in, $key = "userid", $keyIn = "userid", array $fields = ["*"]) {
        $query = $this->select()->setIntegrityCheck(false)
                ->from(["u" => $this->_dbprefix . "users"], $fields)
                ->joinLeft(["r" => $this->_dbprefix . "relations"], "(r.receiver = " . $this->getAdapter()->quote($_SESSION['user']->userid) . " AND r.sender = u.userid) OR (r.sender = " . $this->getAdapter()->quote($_SESSION['user']->userid) . " AND r.receiver = u.userid)")
                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id = u.profileImage", ['filename AS pimg', 'albumid AS palbumid'])
                ->joinLeft(["ti" => $this->_dbprefix . "gallery_images"], "ti.id = u.titleImage", ["filename AS timg", "albumid AS talbumid"])
                ->joinLeft(["p" => $this->_dbprefix . "privacy"], "p.userid=u.userid", new \Zend_Db_Expr("GROUP_CONCAT(CONCAT(p.type,':',p.value)) AS privacy"))
                ->where("u." . $key . " IN(?)", $userids)
                ->where("u.groupid > 0")
                ->where("u." . $keyIn . " IN(?)", $in)
                ->group("u.userid");
        $res = $this->fetchAll($query);
        for ($i = 0; $i < count($res); $i++)
            $res[$i]->privacy = Privacy::parse($res[$i]->privacy);

        return $res;
    }

    /**
     * @param $userid
     * @return bool
     */
    public function exists($userid) {
        return ($this->get($userid) !== NULL);
    }

}
