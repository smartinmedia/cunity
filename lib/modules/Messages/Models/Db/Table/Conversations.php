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

namespace Cunity\Messages\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Notifications\Models\Notifier;

/**
 * Class Conversations
 * @package Cunity\Messages\Models\Db\Table
 */
class Conversations extends Table
{

    /**
     * @var string
     */
    protected $_name = 'conversations';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int|string
     */
    public function getNewConversationId()
    {
        $res = $this->fetchRow($this->select()->from($this, new \Zend_Db_Expr("MAX(conversation_id) as max")));
        if ($res === null) {
            return 1;
        }
        return intval($res->max) + 1;
    }

    /**
     * @param $userid
     * @return int
     */
    public function getConversationId($userid)
    {
        $query = $this->getAdapter()->select()->from(["a" => $this->_dbprefix . "conversations"])->where("userid=?", $userid)->where(new \Zend_Db_Expr("(" . $this->getAdapter()->select()->from(["b" => $this->_dbprefix . "conversations"], new \Zend_Db_Expr("COUNT(*) AS count"))->where("a.conversation_id=b.conversation_id") . ")") . "=2");
        if (!empty($this->getConversationIds())) {
            $query->where("conversation_id IN (" . $this->getConversationIds() . ")");
        }
        $res = $this->getAdapter()->fetchRow($query);
        if ($res === NULL) {
            return 0;
        }
        return $res['conversation_id'];
    }

    /**
     * @return mixed
     */
    public function getConversationIds()
    {
        $res = $this->fetchRow($this->select()->from($this, new \Zend_Db_Expr("GROUP_CONCAT(conversation_id) AS c"))->where("userid=?", $_SESSION['user']->userid))->toArray();
        return $res['c'];
    }

    /**
     * @param $cid
     * @param $users
     * @param bool $invitation
     * @param bool $notify
     * @return bool
     */
    public function addUsersToConversation($cid, $users, $invitation = false, $notify = true)
    {
        if (is_array($users) && !empty($users)) {
            foreach ($users as $user) {
                $this->insert(["userid" => intval($user), "conversation_id" => intval($cid)]);
                if ($notify) {
                    Notifier::notify($user, $_SESSION['user']->userid, "addConversation", "index.php?m=messages&action=" . $cid);
                }
            }
        } else {
            $this->insert(["userid" => intval($users), "conversation_id" => intval($cid)]);
            if ($notify) {
                if ($invitation) {
                    Notifier::notify($_POST['userid'], $_SESSION['user']->userid, "addConversation", "index.php?m=messages&action=" . $cid);
                } else {
                    Notifier::notify($_POST['userid'], $_SESSION['user']->userid, "message", "index.php?m=messages&action=" . $cid);
                }
            }
        }
        return true;
    }

    /**
     * @param $conversation_id
     * @return bool
     */
    public function markAsRead($conversation_id)
    {
        return (0 < $this->update(["status" => 0], [$this->getAdapter()->quoteInto("conversation_id=?", $conversation_id), $this->getAdapter()->quoteInto("userid = ?", $_SESSION['user']->userid)]));
    }

    /**
     * @param $conversation_id
     * @return bool
     */
    public function markAsUnRead($conversation_id)
    {
        return (0 < $this->update(["status" => 1], [$this->getAdapter()->quoteInto("conversation_id=?", $conversation_id), $this->getAdapter()->quoteInto("userid != ?", $_SESSION['user']->userid)]));
    }

    /**
     * @param $conversation_id
     * @return int
     */
    public function deactivateConversation($conversation_id)
    {
        return $this->update(["status" => 2], [$this->getAdapter()->quoteInto("conversation_id=?", $conversation_id), $this->getAdapter()->quoteInto("userid != ?", $_SESSION['user']->userid)]);
    }

    /**
     * @param $conversationid
     * @return mixed
     */
    public function loadConversationDetails($conversationid)
    {
        $result = $this->getAdapter()->fetchRow(
            $this->getAdapter()->select()
                ->from(["c" => $this->_dbprefix . "conversations"], ["(" .
                    new \Zend_Db_Expr($this->getAdapter()->select()
                        ->from(["u" => $this->_dbprefix . "users"], new \Zend_Db_Expr("GROUP_CONCAT(u.userid)"))
                        ->where("u.userid IN (" .
                            new \Zend_Db_Expr($this->getAdapter()->select()
                                ->from(["uc" => $this->_dbprefix . "conversations"], "uc.userid")
                                ->where("uc.conversation_id = c.conversation_id")) . ")")) . ") AS users", "c.conversation_id", "(" .
                    new \Zend_Db_Expr($this->getAdapter()->select()->from($this->_dbprefix . "messages AS cm", [new \Zend_Db_Expr("COUNT(*)")])->where("cm.conversation = c.conversation_id")) . ") AS count"
                ])
                ->where("c.conversation_id=?", intval($conversationid))->where("status < 2")->limit(1)
        );
        return $result;
    }

    /**
     * @param $userid
     * @param int $status
     * @return array
     */
    public function loadConversations($userid, $status = 0)
    {
        $query = $this->getAdapter()->select()
            ->from(
                $this, [
                    "c.status, (" . new \Zend_Db_Expr($this->getAdapter()->select()
                        ->from(["u" => $this->_dbprefix . "users"], new \Zend_Db_Expr("GROUP_CONCAT(CONCAT(u.name,'|',u.userid))"))
                        ->where("u.userid != ?", $userid)
                        ->where("u.userid IN (" .
                            new \Zend_Db_Expr($this->getAdapter()->select()
                                ->from(["uc" => $this->_dbprefix . "conversations"], "uc.userid")
                                ->where("uc.conversation_id = c.conversation_id")) . ")")) . ") AS users"
                ])
            ->where("c.userid=?", $userid)
            ->joinLeft(["m" => $this->_dbprefix . "messages"], "m.conversation=c.conversation_id")
            ->join(["su" => $this->_dbprefix . "users"], "m.sender = su.userid", "su.name AS sendername")
            ->where("m.time = (SELECT MAX(mt.time) FROM " . $this->_dbprefix . "messages AS mt WHERE mt.conversation = c.conversation_id)")
            ->order("m.time DESC");
        if ($status == 0) {
            $query->where("c.`status` < 2");
        } else {
            $query->where($this->getAdapter()->quoteInto("c.`status` = 1", intval($status)));
        }
        //var_dump($query->__toString());
        $result = $this->getAdapter()->fetchAll($query);
        return $result;
    }

    /**
     * @param $userid
     * @param $cid
     * @return bool
     */
    public function leave($userid, $cid)
    {
        return (0 < $this->delete([$this->getAdapter()->quoteInto("userid=?", $userid), $this->getAdapter()->quoteInto("conversation_id=?", $cid)]));
    }
}
