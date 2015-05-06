<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
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

namespace Cunity\Friends\Models\Db\Table;

use Cunity\Core\Cunity;
use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\Models\Db\Table\Users;

/**
 * Class Relationships.
 */
class Relationships extends Table
{
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
    protected $_rowClass = "\Cunity\Friends\Models\Db\Row\Relation";

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $user
     * @param $secondUser
     *
     * @return int
     *
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function deleteRelation($user, $secondUser)
    {
        return $this->getRelation($user, $secondUser)->delete();
    }

    /**
     * @param $user
     * @param $secondUser
     *
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getRelation($user, $secondUser)
    {
        return $this->fetchRow($this->select()->where("sender=$user AND receiver = $secondUser")->orWhere("sender=$secondUser AND receiver = $user"));
    }

    /**
     * @param $user
     * @param $secondUser
     * @param array $updates
     *
     * @return mixed
     */
    public function updateRelation($user, $secondUser, array $updates)
    {
        $rel = $this->getRelation($user, $secondUser);
        if ($rel === null) {
            return $this->addRelation($user, $secondUser, 0);
        } else {
            return $rel->setFromArray($updates)->save();
        }
    }

    /**
     * @param $user
     * @param $secondUser
     * @param $status
     *
     * @return mixed
     */
    public function addRelation($user, $secondUser, $status)
    {
        return $this->insert(['sender' => $user, 'receiver' => $secondUser, 'status' => $status]);
    }

    /**
     * @param string $status
     * @param int    $userid
     *
     * @return array
     */
    public function getFullFriendList($status = '>1', $userid = 0)
    {
        $settings = Cunity::get('settings');

        if ($settings->getSetting('register.allfriends')) {
            /** @var Users $users */
            $users = $_SESSION['user']->getTable();

            return $users->fetchAll('userid!='.$_SESSION['user']->userid)->toArray();
        } elseif (!empty($friends)) {
            $friends = $this->getFriendList($status, $userid);
            $users = $_SESSION['user']->getTable();

            return $users->getSet($friends, 'u.userid', ['u.userid', 'u.username', 'u.name'], true)->toArray();
        }
    }

    /**
     * @param string $status
     * @param int    $userid
     *
     * @return array
     */
    public function getFriendList($status = '>1', $userid = 0)
    {
        if ($userid == 0) {
            $userid = $_SESSION['user']->userid;
        } else {
            $userid = intval($userid);
        }

        // Only user, who blocked another people is allowed to get this list
        if (!is_string($status) && $status == 0) {
            $query = $this->getAdapter()->query('SELECT receiver AS friend FROM '.$this->getTableName().' WHERE '.$this->getAdapter()->quoteInto('sender=?', $userid).' AND STATUS = 0');
        } else {
            $query = $this->getAdapter()->select()
                ->from($this->getTableName(), new \Zend_Db_Expr('(CASE WHEN sender = '.$userid.' THEN receiver WHEN receiver = '.$userid.' THEN sender END) AS friend'))
                ->where('status '.$status)
                ->where('sender=? OR receiver = ? ', $userid);
        }
        $res = $this->getAdapter()->fetchAll($query);
        $result = [];
        foreach ($res as $friend) {
            $result[] = $friend['friend'];
        }

        return $result;
    }

    /**
     * @param int $userid
     *
     * @return array|null
     */
    public function getFullFriendRequests($userid = 0)
    {
        $friends = $this->getFriendRequests($userid);
        if (!empty($friends)) {
            $users = new Users();

            return $users->getSet($friends, 'u.userid', ['u.userid', 'u.username', 'u.name'])->toArray();
        }

        return;
    }

    /**
     * @param int $userid
     *
     * @return array
     */
    public function getFriendRequests($userid = 0)
    {
        if ($userid == 0) {
            $userid = $_SESSION['user']->userid;
        }
        $res = $this->fetchAll($this->select()->from($this, ['sender'])->where('receiver=?', $userid)->where('status=1'));
        $result = [];
        foreach ($res as $friend) {
            $result[] = $friend['sender'];
        }

        return $result;
    }

    /**
     * @param $userid
     *
     * @return array
     */
    public function loadOnlineFriends($userid)
    {
        $friendlist = $this->getAdapter()->fetchAll($this->getAdapter()->select()->from(['u' => $this->_dbprefix.'users'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id = u.profileImage', 'filename AS pimg')
            ->where('u.userid IN ('.new \Zend_Db_Expr($this->getAdapter()->select()
                    ->from($this->_dbprefix.'relations', new \Zend_Db_Expr('(CASE WHEN sender = '.$userid.' THEN receiver WHEN receiver = '.$userid.' THEN sender END)'))
                    ->where('status > 1')
                    ->where('sender=? OR receiver = ? ', $userid)).')')
            ->order('u.name DESC')
        );

        $date = new \DateTime();
        $numberOfFriends = count($friendlist);
        for ($i = 0; $i < $numberOfFriends; $i++) {
            if (null === $friendlist[$i]['lastAction']) {
                $lastAction = 0;
            } else {
                $lastAction = $date->createFromFormat('Y-m-d H:i:s', $friendlist[$i]['lastAction'])->getTimestamp();
            }

            if (time() - 200 > $lastAction) {
                $friendlist[$i]['onlineStatus'] = 2;
            } elseif (time() - 60 > $lastAction) {
                $friendlist[$i]['onlineStatus'] = 0;
            } else {
                $friendlist[$i]['onlineStatus'] = 1;
            }
        }

        return $friendlist;
    }
}
