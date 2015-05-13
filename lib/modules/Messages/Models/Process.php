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

namespace Cunity\Messages\Models;

use Cunity\Core\Request\Get;
use Cunity\Core\Request\Post;
use Cunity\Core\Request\Session;
use Cunity\Core\View\Ajax\View;
use Cunity\Friends\Models\Db\Table\Relationships;
use Cunity\Messages\Models\Db\Table\Conversations;

/**
 * Class Process.
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
        $res = $table->insert(['sender' => Session::get('user')->userid, 'conversation' => Post::get('conversation_id'), 'message' => Post::get('message'), 'source' => Post::get('source')]);
        $conversation = new Conversations();
        if (Post::get('source') === 'chat') {
            $conversation->markAsRead(Post::get('conversation_id'));
        }
        $c = $conversation->loadConversationDetails(Get::get('action'));
        $users = explode(',', $c['users']);
        unset($users[array_search(Session::get('user')->userid, $users)]);
        $view = new View($res !== false);
        $view->addData(['data' => ['conversation_id' => Post::get('conversation_id'), 'message' => Post::get('message'), 'time' => date('Y-m-d H:i:s', time()), 'sender' => Session::get('user')->userid, 'id' => $res]]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function startConversation()
    {
        $conversation_id = 0;
        $messages = new Db\Table\Messages();
        $conv = new Db\Table\Conversations();
        if (count(Post::get('receiver')) == 1) {
            $conversation_id = $conv->getConversationId(intval(Post::get('receiver')[0]));
        }
        if ($conversation_id == 0) {
            $conversation_id = $conv->getNewConversationId();
            Post::get('receiver')[] = Session::get('user')->userid;
            $result = $conv->addUsersToConversation($conversation_id, Post::get('receiver'));
        } else {
            $result = true;
        }
        if ($result) {
            $result = (0 < $messages->insert(['sender' => Session::get('user')->userid, 'conversation' => $conversation_id, 'message' => Post::get('message'), 'source' => Post::get('source')]));
        }
        $view = new View($result);
        $view->sendResponse();
    }

    /**
     *
     */
    private function getConversation()
    {
        $conversation = [];
        $conv = new Db\Table\Conversations();
        $conversation_id = $conv->getConversationId(intval(Post::get('userid')));
        if ($conversation_id == 0) {
            $conversation_id = $conv->getNewConversationId();
            $result = $conv->addUsersToConversation($conversation_id, [Session::get('user')->userid, Post::get('userid')], false);
            $messages = [];
        } else {
            $result = true;
            $m = new Db\Table\Messages();
            $messages = $m->loadByConversation($conversation_id);
        }
        $view = new View($result);
        $data = $conv->loadConversationDetails($conversation_id);
        $conversation['users'] = Session::get('user')->getTable()->getSet(explode(',', $data['users']), 'u.userid', ['u.userid', 'u.username', 'u.name'])->toArray();
        $usernames = '';
        foreach ($conversation['users'] as $user) {
            $usernames .= $user['name'].'|'.$user['userid'].',';
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
        if (Post::hasAction()) {
            $conv = new Db\Table\Conversations();
            $result = $conv->addUsersToConversation(Post::get('conversation_id'), Post::get('receiver'), true);
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
        $result = $messages->delete($messages->getAdapter()->quoteInto('id=?', Post::get('msgid')));
        $view = new View($result !== null);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadConversationMessages()
    {
        $messages = new Db\Table\Messages();
        $result = $messages->loadByConversation(Post::get('conversation_id'), Post::get('offset'), Post::get('refresh'));
        $view = new View($result !== null);
        $view->addData(['messages' => $result]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function leaveConversation()
    {
        $conv = new Db\Table\Conversations();
        $res = false;
        $leaveResult = $conv->leave(Session::get('user')->userid, Post::get('conversation_id'));
        if ($leaveResult) {
            if (Post::get('delMsg') == 'true') {
                $messages = new Db\Table\Messages();
                $res = $messages->deleteByUser(Session::get('user')->userid, Post::get('conversation_id'));
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
        $conversations = $table->loadConversations(Session::get('user')->userid);
        $view = new View(true);
        foreach ($conversations as $i => $conv) {
            $details = $table->loadConversationDetails($conv['conversation_id']);
            if ($details['users'] !== null) {
                $userid = $this->findConversationUser($details);

                if ($userid !== null) {
                    $conversations[$i]['users'] = Session::get('user')->getTable()->get($userid)->toArray(['pimg', 'name']);
                }
            }
        }

        $view->addData(['conversations' => $conversations]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadUnread()
    {
        $table = new Db\Table\Conversations();
        $conversations = $table->loadConversations(Session::get('user')->userid, 1);
        $view = new View(true);
        foreach ($conversations as $i => $conv) {
            if (strpos($conversations[$i]['users'], ',') === false) {
                $userid = explode('|', $conv['users']);
                $conversations[$i]['users'] = Session::get('user')->getTable()->get($userid[1])->toArray(['pimg', 'name']);
            }
        }
        $view->addData(['conversations' => $conversations]);
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
        $friends = $relations->loadOnlineFriends(Session::get('user')->userid);
        $conversations = $table->loadConversations(Session::get('user')->userid, 1);
        $view = new View(true);
        foreach ($conversations as $i => $conv) {
            $conversations[$i]['messages'] = $messages->loadByConversation($conv['conversation'], 0, (Post::get('chatboxes') !== null && is_array(Post::get('chatboxes')) && array_key_exists($conv['conversation'], Post::get('chatboxes'))) ? Post::get('chatboxes')[$conv['conversation']] : 0);
        }
        $view->addData(['conversations' => $conversations, 'users' => $friends]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function markAsRead()
    {
        $conversation = new Db\Table\Conversations();
        $view = new View($conversation->markAsRead(Post::get('conversation_id')));
        $view->sendResponse();
    }

    /**
     * @param $details
     *
     * @return array
     */
    private function findConversationUser($details)
    {
        $userid = [];
        $id = null;

        if (strpos($details['users'], ',') !== false) {
            $userid = explode(',', $details['users']);
        } elseif (strpos($details['users'], '|') !== false) {
            $userid = explode('|', $details['users']);
        }

        foreach ($userid as $id) {
            if ($id != Session::get('user')->userid) {
                break;
            }
        }

        return $id;
    }
}
