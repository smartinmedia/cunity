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

namespace Cunity\Newsfeed\Models;

use Cunity\Core\View\Ajax\View;

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
        $view = new View();
        if (empty($_POST['content'])) {
            $view->setStatus(false);
            $view->addData(['msg' => $view->translate('Oups! Your post is empty!')]);
        } else {
            $table = new Db\Table\Posts();
            $videoData = ['video' => json_decode(html_entity_decode($_POST['youtubedata']), true), 'content' => $_POST['content']];
            $content = ($_POST['type'] == 'video') ? json_encode($videoData) : $_POST['content'];
            $res = $table->post(['userid' => $_SESSION['user']->userid, 'wall_owner_id' => $_POST['wall_owner_id'], 'wall_owner_type' => $_POST['wall_owner_type'], 'privacy' => $_POST['privacy'], 'content' => $content, 'type' => $_POST['type']]);
            $view->setStatus($res !== false);
            if ($_POST['type'] == 'video') {
                $view->addData(array_merge($res, ['content' => $videoData]));
            } else {
                $view->addData($res);
            }
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function delete()
    {
        $table = new Db\Table\Posts();
        $res = $table->deletePost($_POST['id']);
        $view = new View($res);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadPost()
    {
        $table = new Db\Table\Posts();
        $res = $table->loadPost($_POST['postid']);
        $view = new View($res !== null);
        $view->addData($res);
        $view->sendResponse();
    }

    /**
     *
     */
    private function load()
    {
        if (!isset($_POST['wall_owner_id']) || $_POST['wall_owner_id'] == 0) {
            $newsfeed = new Db\Table\Walls();
            $res = $newsfeed->getNewsfeed($_POST['offset'], $_POST['refresh'], $_POST['filter']);
            $view = new View(true);
            $view->addData(['posts' => $res]);
            $view->sendResponse();
        } elseif (isset($_POST['wall_owner_id']) && $_POST['wall_owner_id'] > 0 && isset($_POST['wall_owner_type']) && !empty($_POST['wall_owner_type'])) {
            $newsfeed = new Db\Table\Walls();
            $res = $newsfeed->getWall($_POST['wall_owner_id'], $_POST['wall_owner_type'], $_POST['offset'], $_POST['refresh'], $_POST['filter']);
            $view = new View(true);
            $view->addData(['posts' => $res]);
            $view->sendResponse();
        }
    }
}
