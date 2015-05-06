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

namespace Cunity\Messages\Models;

use Cunity\Core\Exceptions\PageNotFound;
use Cunity\Core\Models\Db\Table\Users;

/**
 * Class Conversation.
 */
class Conversation
{
    /**
     *
     */
    public function __construct()
    {
        $this->load();
    }

    /**
     *
     */
    private function load()
    {
        $table = new Db\Table\Conversations();
        $userTable = new Users();
        $view = new \Cunity\Messages\View\Conversation();
        $conversation = $table->loadConversationDetails($_GET['action']);
        $users = explode(',', $conversation['users']);
        if (!in_array($_SESSION['user']->userid, $users)) {
            throw new PageNotFound();
        } else {
            unset($users[array_search($_SESSION['user']->userid, $users)]);
        }
        $table->markAsRead($_GET['action']);
        if (!empty($users)) {
            $conversation['users'] = $userTable->getSet($users, 'u.userid', ['u.userid', 'u.username', 'u.name'])->toArray();
            $usernames = '';
            foreach ($conversation['users'] as $user) {
                $usernames .= $user['name'].',';
            }
            $view->setMetaData(['title' => substr($usernames, 0, -1)]);
        } else {
            $view->setMetaData(['title' => 'Conversation']);
        }
        $view->assign('conversation', $conversation);
        $view->show();
    }
}
