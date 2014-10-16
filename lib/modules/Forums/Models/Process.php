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

namespace Cunity\Forums\Models;

use Cunity\Core\Models\Generator\Url;
use Cunity\Core\View\Ajax\View;
use Cunity\Core\View\PageNotFound;
use Cunity\Forums\Models\Db\Table\Boards;
use Cunity\Forums\Models\Db\Table\Categories;
use Cunity\Forums\Models\Db\Table\Forums;
use Cunity\Forums\Models\Db\Table\Posts;
use Cunity\Forums\Models\Db\Table\Threads;
use Cunity\Forums\View\Board;
use Cunity\Forums\View\Category;
use Cunity\Forums\View\Forum;
use Cunity\Forums\View\Thread;

/**
 * Class Process
 * @package Cunity\Forums\Models
 */
class Process
{

    /**
     *
     * @param String $action
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
    public function deleteForum()
    {
        $forums = new Forums;
        $view = new View(false);
        if ($_SESSION['user']->isAdmin()) {
            $view->setStatus($forums->deleteForum($_POST['id']));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    public function deleteBoard()
    {
        $boards = new Boards;
        $view = new View(false);
        if ($_SESSION['user']->isAdmin()) {
            $view->setStatus($boards->deleteBoard($_POST['id']));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    public function deleteThread()
    {
        $threads = new Threads;
        $view = new View(false);
        if ($_SESSION['user']->isAdmin()) {
            $view->setStatus($threads->deleteThread($_POST['id']));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadForums()
    {
        $forums = new Forums;
        $topics = new Boards;
        $res = $forums->loadForums();
        if ($res !== null && $res !== false) {
            $resCount = count($res);
            for ($i = 0; $i < $resCount; $i++) {
                $res[$i]["boards"] = $topics->loadBoards($res[$i]["id"]);
            }
        }
        $view = new View($res !== null && $res !== false);
        $view->addData(["result" => $res]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadBoards()
    {
        $boards = new Boards;
        $res = $boards->loadBoards($_POST['id']);
        $view = new View($res !== null && $res !== false);
        $view->addData(["result" => $res]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadPosts()
    {
        $posts = new Posts;
        $res = $posts->loadPosts($_POST['id'], 20, ($_POST['page'] - 1) * 20);
        $view = new View($res !== null && $res !== false);
        if ($res !== false) {
            foreach ($res as $i => $r) {
                $res[$i]['content'] = $this->quote(htmlspecialchars_decode($r['content']));
            }
            $view->addData(["result" => $res]);
        }
        $view->sendResponse();
    }

    /**
     * @param $str
     * @return mixed
     */
    private function quote($str)
    {
        $format_search = [];
        $format_replace = [];
        if (preg_match_all('#\[quote=(.*?)\](.*?)#is', $str, $matches1, PREG_SET_ORDER) == preg_match_all('#\[/quote\]#is', $str, $matches2)) {
            if (empty($matches1)) {
                return $str;
            }
            array_push($format_search, '#\[quote=(.*?)\](.*?)#is');
            array_push($format_search, '#\[/quote\]#is');
            $user = $_SESSION['user']->getTable()->get($matches1[0][1], "username");
            array_push($format_replace, '<div class="quotation well well-sm"><a class="quotation-user" href="' . Url::convertUrl("index.php?m=profile&action=" . $user->username) . '">' . $user->name . ':</a>$2');
            array_push($format_replace, '</div>');
        }

        return preg_replace($format_search, $format_replace, $str);
    }

    /**
     *
     */
    private function forum()
    {
        $boards = new Forums;
        $data = $boards->loadForumData($_GET['x']);
        if ($data === false) {
            new PageNotFound;
        }
        $view = new Forum();
        $view->setMetaData(["title" => $data['title']]);
        $view->assign("forum", $data);
        $view->show();
    }

    /**
     *
     */
    private function board()
    {
        $boards = new Boards;
        $cat = new Categories;
        $data = $boards->loadBoardData($_GET['x']);
        if ($data === false) {
            new PageNotFound;
        }
        $view = new Board();
        $view->setMetaData(["title" => $data['title']]);

        $view->assign("categories", $cat->getCategories());
        $view->assign("board", $data);
        $view->show();
    }

    /**
     *
     */
    private function thread()
    {
        $threads = new Threads;
        $cat = new Categories;
        $data = $threads->loadThreadData($_GET['x']);
        if ($data === false) {
            new PageNotFound;
        }
        $view = new Thread();
        $view->setMetaData(["title" => $data['title']]);
        $view->assign("thread", $data);
        $view->assign("categories", $cat->getCategories());
        $view->show();
    }

    /**
     *
     */
    private function category()
    {
        if (!isset($_GET['x']) || empty($_GET['x'])) {
            new PageNotFound;
        }
        $cat = new Categories;
        $data = $cat->getCategoryData($_GET['x']);
        if ($data === false) {
            new PageNotFound;
        }
        $view = new Category();
        $view->setMetaData(["title" => $view->translate("Category") . ": " . $data['name']]);
        $view->assign("category", $data);
        $view->show();
    }

    /**
     *
     */
    private function loadThreads()
    {
        $threads = new Threads;
        if (isset($_POST['id'])) {
            $res = $threads->loadThreads($_POST['id']);
        } elseif (isset($_POST['cat'])) {
            $res = $threads->loadCategoryThreads($_POST['cat']);
        }
        $view = new View($res !== null && $res !== false);
        if ($res !== false) {
            foreach ($res as $i => $r) {
                $res[$i]['content'] = strip_tags(htmlspecialchars_decode($r['content']));
            }
            $view->addData(["result" => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function createForum()
    {
        $forums = new Forums;
        $res = $forums->add(["title" => $_POST['title'], "description" => $_POST['description'], "board_permissions" => (isset($_POST['board_permissions'])) ? $_POST['board_permissions'] : 0]);
        $view = new View($res !== false);
        if ($res !== false) {
            $view->addData(["forum" => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function createBoard()
    {
        $forums = new Boards;
        $res = $forums->add(["title" => $_POST['title'], "description" => $_POST['description'], "forum_id" => $_POST['forum_id']]);
        $view = new View($res !== false);
        if ($res !== false) {
            $view->addData(["board" => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function startThread()
    {
        $threads = new Threads;
        $posts = new Posts;
        $res = $threads->insert([
            "title" => $_POST['title'],
            "board_id" => $_POST['board_id'],
            "userid" => $_SESSION['user']->userid,
            "category" => $_POST['category'],
            "important" => (isset($_POST['important']) && $_SESSION['user']->isAdmin()) ? $_POST['important'] : 0
        ]);
        $view = new View(false);
        if ($res !== false) {
            $postRes = $posts->post(["content" => $_POST['content'], "thread_id" => $res, "userid" => $_SESSION['user']->userid]);
            $view->setStatus($postRes !== false);
            $view->addData(["id" => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function postReply()
    {
        $posts = new Posts;
        $res = $posts->post($_POST);
        $view = new View(false);
        if ($res !== false) {
            $view->setStatus(true);
            $res['content'] = $this->quote(htmlspecialchars_decode($res['content']));
            $view->addData(["post" => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadCategories()
    {
        $cat = new Categories();
        $res = $cat->getCategories();
        $view = new View($res !== false);
        if ($res !== false) {
            $view->addData(["result" => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function editForum()
    {
        $forums = new Forums;
        $res = $forums->update(["title" => $_POST['title'], "description" => $_POST['description'], "board_permissions" => (isset($_POST['board_permissions'])) ? $_POST['board_permissions'] : 0], $forums->getAdapter()->quoteInto("id=?", $_POST['forum_id']));
        $view = new View($res !== false && $res > 0);
        $view->sendResponse();
    }

    /**
     *
     */
    private function editBoard()
    {
        $boards = new Boards;
        $res = $boards->update(["title" => $_POST['title'], "description" => $_POST['description']], $boards->getAdapter()->quoteInto("id=?", $_POST['board_id']));
        $view = new View($res !== false && $res > 0);
        $view->sendResponse();
    }

    /**
     *
     */
    private function deletePost()
    {
        $posts = new Posts;
        $data = $posts->getPost($_POST['id']);
        $view = new View(false);
        if ($_SESSION['user']->isAdmin() || $data['userid'] == $_SESSION['user']->userid) {
            $view->setStatus($posts->deletePost($_POST['id']));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function editThread()
    {
        $threads = new Threads;
        $res = $threads->update([
            "title" => $_POST['title'],
            "category" => $_POST['category'],
            "important" => (isset($_POST['important']) && $_SESSION['user']->isAdmin()) ? $_POST['important'] : 0
        ], $threads->getAdapter()->quoteInto("id=?", $_POST['thread_id']));
        $view = new View($res !== false);
        $view->sendResponse();
    }
}
