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

namespace Cunity\Core\Models\Db\Row;

use Cunity\Core\Cunity;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Gallery\Models\Db\Table\Gallery_Images;
use Cunity\Friends\Models\Db\Table\Relationships;
use Cunity\Search\Models\Process;

/**
 * Class User
 * @package Cunity\Core\Models\Db\Row
 */
class User extends \Zend_Db_Table_Row_Abstract
{

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
    public function init()
    {
        parent::init();
    }

    /**
     * @param $password
     * @return bool
     */
    public function passwordMatch($password)
    {
        return (sha1($password . $this->salt) == $this->password);
    }

    /**
     * @param bool $cookie
     */
    public function setLogin($cookie = false)
    {
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
    public function logout()
    {
        session_destroy();
        setcookie("cunity-login", base64_encode($this->username), time() - 3600, '/', Cunity::get("settings")->getSetting("core.siteurl"));
        setcookie("cunity-login-token", md5($this->salt . "-" . $this->registered . "-" . $this->userhash), time() - 3600, '/', Cunity::get("settings")->getSetting("core.siteurl"));
    }

    /**
     * @return array
     */
    public function getProfileImages()
    {
        if ($this->images !== null)
            return $this->images;
        $images = new Gallery_Images();
        $this->images = $images->fetchAll($images->select()->where("id=?", $this->profileImage)->orWhere("id=?", $this->titleImage));
        return $this->images;
    }

    /**
     * @return array
     */
    public function getFriendList()
    {
        $rel = new Relationships();
        return $rel->getFriendList(">1", $this->userid);
    }

    /**
     * @param int $user
     * @return array
     */
    public function getRelationship($user = 0)
    {
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
    public function isFriend($user = 0)
    {
        $r = $this->getRelationship($user);
        if (!empty($r) && $r["status"] == 2)
            return true;
        return false;
    }

    /**
     * @throws \Exception
     */
    private function setCookie()
    {
        $expire = time() + 3600 * 24 * 30;
        setcookie("cunity-login", base64_encode($this->username), $expire, '/', Cunity::get("settings")->getSetting("core.siteurl"));
        setcookie("cunity-login-token", md5($this->salt . "-" . $this->registered . "-" . $this->userhash), $expire, '/', Cunity::get("settings")->getSetting("core.siteurl"));
    }

    /**
     * @param array $args
     * @return array
     */
    public function toArray(array $args = [])
    {
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
    public function isAdmin()
    {
        return $this->groupid == 3 || $this->groupid == 2;
    }

    /**
     * @return bool|mixed
     */
    public function save()
    {
        if (isset($this->_modifiedFields['username']) ||
            isset($this->_modifiedFields['firstname']) ||
            isset($this->_modifiedFields['lastname'])
        ) {
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
    public function __wakeup()
    {
        if ($this->_table == null)
            $this->setTable(new Users());
        $this->lastAction = new \Zend_Db_Expr("UTC_TIMESTAMP()");
        $this->onlineStatus = intval(!isset($_POST['inactive']));
        $this->save();
    }

}
