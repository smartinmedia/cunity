<?php

namespace Core\Models\Db\Row;

use Core\Cunity;
use Core\Models\Db\Table\Users;
use \Gallery\Models\Db\Table\Gallery_Images;
use \Friends\Models\Db\Table\Relationships;
use Search\Models\Process;

/**
 * Class User
 * @package Core\Models\Db\Row
 */
class User extends \Zend_Db_Table_Row_Abstract {

    /**
     * @var array
     */
    protected $images = [];
    /**
     * @var array
     */
    public $friends = [];

    /**
     *
     */
    public function init() {
        parent::init();
    }

    /**
     * @param $password
     * @return bool
     */
    public function passwordMatch($password) {
        return (sha1($password . $this->salt) == $this->password);
    }

    /**
     * @param bool $cookie
     */
    public function setLogin($cookie = false) {
        if ($cookie)
            $this->setCookie();
        $this->password_token = NULL;
        $this->save();
        $_SESSION['loggedIn'] = true;
        $_SESSION['user'] = $this;
    }

    /**
     * @throws \Exception
     */
    public function logout() {
        session_destroy();
        setcookie("cunity-login", base64_encode($this->username), time() - 3600, '/', Cunity::get("settings")->getSetting("core.siteurl"));
        setcookie("cunity-login-token", md5($this->salt . "-" . $this->registered . "-" . $this->userhash), time() - 3600, '/', Cunity::get("settings")->getSetting("core.siteurl"));
    }

    /**
     * @return array
     */
    public function getProfileImages() {
        if ($this->images !== null)
            return $this->images;
        $images = new Gallery_Images();
        $this->images = $images->fetchAll($images->select()->where("id=?", $this->profileImage)->orWhere("id=?", $this->titleImage));
        return $this->images;
    }

    /**
     * @return array
     */
    public function getFriendList() {
        $rel = new Relationships();
        return $rel->getFriendList(">1", $this->userid);
    }

    /**
     * @param int $user
     * @return array
     */
    public function getRelationship($user = 0) {
        if ($user == 0)
            $user = $_SESSION['user']->userid;
        $rel = new Relationships();
        $result = $rel->getRelation($this->userid, $user);
        if ($result == NULL)
            return [];
        else
            return $result->toArray();
    }

    /**
     * @param int $user
     * @return bool
     */
    public function isFriend($user = 0) {
        $r = $this->getRelationship($user);
        if (!empty($r) && $r["status"] == 2)
            return true;
        return false;
    }

    /**
     * @throws \Exception
     */
    private function setCookie() {
        $expire = time() + 3600 * 24 * 30;
        setcookie("cunity-login", base64_encode($this->username), $expire, '/', Cunity::get("settings")->getSetting("core.siteurl"));
        setcookie("cunity-login-token", md5($this->salt . "-" . $this->registered . "-" . $this->userhash), $expire, '/', Cunity::get("settings")->getSetting("core.siteurl"));
    }

    /**
     * @param array $args
     * @return array
     */
    public function toArray(array $args = []) {
        if (empty($args))
            return parent::toArray();
        $result = [];
        foreach ($args AS $v)
            $result[$v] = $this->_data[$v];
        return $result;
    }

    /**
     * @return bool
     */
    public function isAdmin() {
        return $this->groupid == 3 || $this->groupid == 2;
    }

    /**
     * @return bool|mixed
     */
    public function save() {
        if (isset($this->_modifiedFields['username']) ||
                isset($this->_modifiedFields['firstname']) ||
                isset($this->_modifiedFields['lastname'])) {
            $currentUsername = $this->username;
            $result = parent::save();
            $searchindex = new Process();
            return $result && $searchindex->updateUser($currentUsername, $this->username, $this->firstname . " " . $this->lastname);
        } else
            return parent::save();
    }

    /**
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function __wakeup() {
        if ($this->_table == null)
            $this->setTable(new Users());
        $this->lastAction = new \Zend_Db_Expr("UTC_TIMESTAMP()");
        $this->onlineStatus = intval(!isset($_POST['inactive']));
        $this->save();
    }

}
