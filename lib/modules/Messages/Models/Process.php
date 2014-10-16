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

namespace Cunity\Messages\Models;

use Cunity\Core\View\Ajax\View;
use Cunity\Friends\Models\Db\Table\Relationships;
use Cunity\Messages\Models\Db\Table\Conversations;

/**
 * Class Process
 * @package Cunity\Messages\Models
 */
class Process
{

    /**
     * @param $action
     */
    public function __construct($action)
    {
        if (method_exists($this, $action)) {
            call_user_func([$this, $action]);
        }
    }

    /**
     *
     */
    private function send()
    {
        $table = new Db\Table\Messages();
        $res = $table->insert(["sender" => $_SESSION['user']->userid, "conversation" => $_POST['conversation_id'], "message" => $_POST['message'], "source" => $_POST['source']]);
        $conversation = new Conversations();
        if ($_POST['source'] == "chat") {
            $conversation->markAsRead($_POST['conversation_id']);
        }
        $c = $conversation->loadConversationDetails($_GET['action']);
        $users = explode(",", $c['users']);
        unset($users[array_search($_SESSION['user']->userid, $users)]);
        $view = new View($res !== false);
        $view->addData(["data" => ["conversation_id" => $_POST['conversation_id'], "message" => $_POST['message'], "time" => date("Y-m-d H:i:s", time()), "sender" => $_SESSION['user']->userid, "id" => $res]]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function startConversation()
    {
        $messages = new Db\Table\Messages();
        $conv = new Db\Table\Conversations();
        if (count($_POST['receiver']) == 1) {
            $conversation_id = $conv->getConversationId(intval($_POST['receiver'][0]));
        }
        if ($conversation_id == 0) {
            $conversation_id = $conv->getNewConversationId();
            $_POST['receiver'][] = $_SESSION['user']->userid;
            $result = $conv->addUsersToConversation($conversation_id, $_POST['receiver']);
        } else {
            $result = true;
        }
        if ($result) {
            $result = (0 < $messages->insert(["sender" => $_SESSION['user']->userid, "conversation" => $conversation_id, "message" => $_POST['message'], "source" => $_POST['source']]));
        }
        $view = new View($result);
        $view->sendResponse();
    }

    /**
     *
     */
    private function getConversation()
    {
        $conv = new Db\Table\Conversations();
        $conversation_id = $conv->getConversationId(intval($_POST['userid']));
        if ($conversation_id == 0) {
            $conversation_id = $conv->getNewConversationId();
            $result = $conv->addUsersToConversation($conversation_id, [$_SESSION['user']->userid, $_POST['userid']], false);
            $messages = [];
        } else {
            $result = true;
            $m = new Db\Table\Messages();
            $messages = $m->loadByConversation($conversation_id);
        }
        $view = new View($result);
        $data = $conv->loadConversationDetails($conversation_id);
        $conversation['users'] = $_SESSION['user']->getTable()->getSet(explode(",", $data['users']), "u.userid", ["u.userid", "u.username", "u.name"])->toArray();
        $usernames = "";
        foreach ($conversation['users'] as $user) {
            $usernames .= $user['name'] . '|' . $user['userid'] . ",";
        }
        $data['users'] = substr($usernames, 0, -1);
        $data['messages'] = $messages;
        $view->addData($data);
        $view->sendResponse();
    }

    /**
     *
     */
    private function invite()
    {
        if (isset($_POST['receiver']) && !empty($_POST['receiver'])) {
            $conv = new Db\Table\Conversations();
            $result = $conv->addUsersToConversation($_POST['conversation_id'], $_POST['receiver'], true);
            $view = new View($result);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function deletemessage()
    {
        $messages = new Db\Table\Messages();
        $result = $messages->delete($messages->getAdapter()->quoteInto("id=?", $_POST['msgid']));
        $view = new View($result !== null);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadConversationMessages()
    {
        $messages = new Db\Table\Messages();
        $result = $messages->loadByConversation($_POST['conversation_id'], $_POST['offset'], $_POST['refresh']);
        $view = new View($result !== null);
        $view->addData(["messages" => $result]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function leaveConversation()
    {
        $conv = new Db\Table\Conversations();
        $res = false;
        if ($conv->leave($_SESSION['user']->userid, $_POST['conversation_id'])) {
            if ($_POST['delMsgs'] == "true") {
                $messages = new Db\Table\Messages();
                $res = $messages->deleteByUser($_SESSION['user']->userid, $_POST['conversation_id']);
            } else {
                $res = true;
            }
        }
        $view = new View($res);
        $view->sendResponse();
    }

    /**
     *
     */
    private function load()
    {
        $table = new Db\Table\Conversations();
        $conversations = $table->loadConversations($_SESSION['user']->userid);
        $view = new View(true);
        foreach ($conversations as $i => $conv) {
            if ($conv['users'] !== null && strpos($conv['users'], ",") === false) {
                $userid = explode("|", $conv['users']);
                $conversations[$i]['users'] = $_SESSION['user']->getTable()->get($userid[1])->toArray(["pimg", "name"]);
            }
        }
        $view->addData(["conversations" => $conversations]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadUnread()
    {
        $table = new Db\Table\Conversations();
        $conversations = $table->loadConversations($_SESSION['user']->userid, 1);
        $view = new View(true);
        foreach ($conversations as $i => $conv) {
            if (strpos($conversations[$i]['users'], ",") === false) {
                $userid = explode("|", $conv['users']);
                $conversations[$i]['users'] = $_SESSION['user']->getTable()->get($userid[1])->toArray(["pimg", "name"]);
            }
        }
        $view->addData(["conversations" => $conversations]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function chatHearthBeat()
    {
        $relations = new Relationships();
        $table = new Db\Table\Conversations();
        $messages = new Db\Table\Messages();
        $friends = $relations->loadOnlineFriends($_SESSION['user']->userid);
        $conversations = $table->loadConversations($_SESSION['user']->userid, 1);
        $view = new View(true);
        foreach ($conversations as $i => $conv) {
            $conversations[$i]['messages'] = $messages->loadByConversation($conv['conversation'], 0, (isset($_POST['chatboxes']) && is_array($_POST['chatboxes']) && array_key_exists($conv['conversation'], $_POST['chatboxes'])) ? $_POST['chatboxes'][$conv['conversation']] : 0);
        }
        $view->addData(["conversations" => $conversations, "users" => $friends]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function markAsRead()
    {
        $conversation = new Db\Table\Conversations();
        $view = new View($conversation->markAsRead($_POST['conversation_id']));
        $view->sendResponse();
    }
}
