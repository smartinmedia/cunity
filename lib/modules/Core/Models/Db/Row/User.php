<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2015 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################.
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
use Cunity\Core\Request\Post;
use Cunity\Core\Request\Session;
use Cunity\Friends\Models\Db\Table\Relationships;
use Cunity\Gallery\Models\Db\Table\GalleryImages;
use Cunity\Profile\Models\Db\Table\ProfileFieldsUsers;
use Cunity\Search\Models\Process;

/**
 * Class User.
 */
class User extends \Zend_Db_Table_Row_Abstract
{
    /**
     * @var array
     */
    public $friends = [];

    /**
     * @var array
     */
    protected $images = [];

    /**
     * @var array
     */
    public static $fixedProfileFields = ['userhash', 'lang', 'username', 'name', 'email', 'password', 'firstname', 'lastname', 'profileImage', 'titleImage', 'groupid', 'salt', 'registered', 'lastAction', 'onlineStatus', 'chat_available', 'password_token'];

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @param $password
     *
     * @return bool
     */
    public function passwordMatch($password)
    {
        return (sha1($password.$this->salt) == $this->password);
    }

    /**
     * @param bool $cookie
     */
    public function setLogin($cookie = false)
    {
        if ($cookie) {
            $this->setCookie();
        }
        $this->password_token = null;
        $this->save();
        Session::set('loggedIn', true);
        Session::set('user', $this);
    }

    /**
     * @throws \Exception
     */
    private function setCookie()
    {
        $expire = time() + 3600 * 24 * 30;
        setcookie('cunity-login', base64_encode($this->username), $expire, '/', Cunity::get('settings')->getSetting('core.siteurl'));
        setcookie('cunity-login-token', md5($this->salt.'-'.$this->registered.'-'.$this->userhash), $expire, '/', Cunity::get('settings')->getSetting('core.siteurl'));
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

            return $result && $searchindex->updateUser($currentUsername, $this->username, $this->firstname.' '.$this->lastname);
        } elseif (Post::get('field') !== null) {
            $profileFieldsUser = new ProfileFieldsUsers([], $this);

            return $profileFieldsUser->update(Post::get('field', []), '');
        } else {
            return parent::save();
        }
    }

    /**
     * @throws \Exception
     */
    public function logout()
    {
        session_destroy();
        setcookie('cunity-login', base64_encode($this->username), time() - 3600, '/', Cunity::get('settings')->getSetting('core.siteurl'));
        setcookie('cunity-login-token', md5($this->salt.'-'.$this->registered.'-'.$this->userhash), time() - 3600, '/', Cunity::get('settings')->getSetting('core.siteurl'));
    }

    /**
     * @return array
     */
    public function getProfileImages()
    {
        if ($this->images !== null) {
            return $this->images;
        }
        $images = new GalleryImages();
        $this->images = $images->fetchAll($images->select()->where('id=?', $this->profileImage)->orWhere('id=?', $this->titleImage))->toArray();

        return $this->images;
    }

    /**
     * @return array
     */
    public function getFriendList()
    {
        $rel = new Relationships();

        return $rel->getFriendList('>1', $this->userid);
    }

    /**
     * @param int $user
     *
     * @return bool
     */
    public function isFriend($user = 0)
    {
        $r = $this->getRelationship($user);
        if (!empty($r) && $r['status'] == 2) {
            return true;
        }

        return false;
    }

    /**
     * @param int $user
     *
     * @return array
     */
    public function getRelationship($user = 0)
    {
        if ($user == 0) {
            $user = Session::get('user')->userid;
        }

        $rel = new Relationships();
        $result = $rel->getRelation($this->userid, $user);
        if ($result === null) {
            return [];
        } else {
            return $result->toArray();
        }
    }

    /**
     * @param array $args
     *
     * @return array
     */
    public function toArray(array $args = [])
    {
        if (empty($args)) {
            return parent::toArray();
        }
        $result = [];
        foreach ($args as $v) {
            $result[$v] = $this->_data[$v];
        }

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
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function __wakeup()
    {
        if ($this->_table === null) {
            $this->setTable(new Users());
        }
        $this->lastAction = new \Zend_Db_Expr('UTC_TIMESTAMP()');
        $this->onlineStatus = intval(Post::get('inactive') === null);
        $this->save();
    }
}
