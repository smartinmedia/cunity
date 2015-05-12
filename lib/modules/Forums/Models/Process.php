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

namespace Cunity\Forums\Models;

use Cunity\Core\Exceptions\CategoryNotFound;
use Cunity\Core\Exceptions\ForumNotFound;
use Cunity\Core\Exceptions\PageNotFound;
use Cunity\Core\Exceptions\ThreadNotFound;
use Cunity\Core\Helper\UserHelper;
use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\Request\Post;
use Cunity\Core\Request\Session;
use Cunity\Core\View\Ajax\View;
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
 * Class Process.
 */
class Process
{
    /**
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
        $forums = new Forums();
        $view = new View(false);
        if (UserHelper::isAdmin()) {
            $view->setStatus($forums->deleteForum(Post::get('id')));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    public function deleteBoard()
    {
        $boards = new Boards();
        $view = new View(false);
        if (UserHelper::isAdmin()) {
            $view->setStatus($boards->deleteBoard(Post::get('id')));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    public function deleteThread()
    {
        $threads = new Threads();
        $view = new View(false);
        if (UserHelper::isAdmin()) {
            $view->setStatus($threads->deleteThread(Post::get('id')));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadForums()
    {
        $forums = new Forums();
        $topics = new Boards();
        $res = $forums->loadForums();
        if ($res !== null && $res !== false) {
            $resCount = count($res);
            for ($i = 0; $i < $resCount; $i++) {
                $res[$i]['boards'] = $topics->loadBoards($res[$i]['id']);
            }
        }
        $view = new View($res !== null && $res !== false);
        $view->addData(['result' => $res]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadPosts()
    {
        $posts = new Posts();
        $res = $posts->loadPosts(Post::get('id'), 20, (Post::get('page') - 1) * 20);
        $view = new View($res !== null && $res !== false);
        if ($res !== false) {
            foreach ($res as $i => $r) {
                $res[$i]['content'] = $this->quote(htmlspecialchars_decode($r['content']));
            }
            $view->addData(['result' => $res]);
        }
        $view->sendResponse();
    }

    /**
     * @param $str
     *
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
            $user = Session::get('user')->getTable()->get($matches1[0][1], 'username');
            array_push($format_replace, '<div class="quotation well well-sm"><a class="quotation-user" href="'.Url::convertUrl('index.php?m=profile&action='.$user->username).'">'.$user->name.':</a>$2');
            array_push($format_replace, '</div>');
        }

        return preg_replace($format_search, $format_replace, $str);
    }

    /**
     *
     */
    private function forum()
    {
        $boards = new Forums();
        $data = $boards->loadForumData(Get::get('x'));
        if ($data === false) {
            throw new ForumNotFound();
        }
        $view = new Forum();
        $view->setMetaData(['title' => $data['title']]);
        $view->assign('forum', $data);
        $view->show();
    }

    /**
     *
     */
    private function board()
    {
        $this->showData('board');
    }

    /**
     * @param $type
     */
    private function showData($type)
    {
        $data = false;
        $cat = new Categories();
        $view = new View();
        switch ($type) {
            case 'board':
                $boards = new Boards();
                $data = $boards->loadBoardData(Get::get('x'));
                $view = new Board();
                $view->assign('board', $data);
                if ($data === false) {
                    throw new ForumNotFound();
                }
                break;
            case 'thread':
                $threads = new Threads();
                $data = $threads->loadThreadData(Get::get('x'));
                $view = new Thread();
                $view->assign('thread', $data);
                if ($data === false) {
                    throw new ThreadNotFound();
                }
                break;
        }

        $view->setMetaData(['title' => $data['title']]);
        $view->assign('categories', $cat->getCategories());

        $view->show();
    }

    /**
     *
     */
    private function thread()
    {
        $this->showData('thread');
    }

    /**
     *
     */
    private function category()
    {
        if (!isset(Get::get('x')) || empty(Get::get('x'))) {
            throw new PageNotFound();
        }
        $cat = new Categories();
        $data = $cat->getCategoryData(Get::get('x'));
        if ($data === false) {
            throw new CategoryNotFound();
        }
        $view = new Category();
        $view->setMetaData(['title' => $view->translate('Category').': '.$data['name']]);
        $view->assign('category', $data);
        $view->show();
    }

    /**
     *
     */
    private function loadThreads()
    {
        $res = false;
        $threads = new Threads();
        if (Post::get('id') !== null) {
            $res = $threads->loadThreads(Post::get('id'));
        } elseif (Post::get('cat') !== null) {
            $res = $threads->loadCategoryThreads(Post::get('cat'));
        }
        $view = new View($res !== null && $res !== false);
        if ($res !== false) {
            foreach ($res as $i => $r) {
                $res[$i]['content'] = strip_tags(htmlspecialchars_decode($r['content']));
            }
            $view->addData(['result' => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function createForum()
    {
        $res = $this->create(new Forums(), ['title' => Post::get('title'), 'description' => Post::get('description'), 'board_permissions' => (Post::get('board_permissions') !== null) ? Post::get('board_permissions') : 0]);
        $view = new View($res !== false);
        if ($res !== false) {
            $view->addData(['forum' => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function createBoard()
    {
        $res = $this->create(new Boards(), ['title' => Post::get('title'), 'description' => Post::get('description'), 'forum_id' => Post::get('forum_id')]);
        $view = new View($res !== false);
        if ($res !== false) {
            $view->addData(['board' => $res]);
        }
        $view->sendResponse();
    }

    /**
     * @param Table $object
     * @param array $data
     *
     * @return mixed
     */
    private function create(Table $object, $data = [])
    {
        return $object->add($data);
    }

    /**
     *
     */
    private function startThread()
    {
        $threads = new Threads();
        $posts = new Posts();
        $category = Post::get('category');

        if ($category === null) {
            $category = '';
        }

        if ((Post::get('important') !== null && UserHelper::isAdmin())) {
            $res = $threads->insert([
                'title' => Post::get('title'),
                'board_id' => Post::get('board_id'),
                'userid' => Session::get('user')->userid,
                'category' => $category,
                'important' => Post::get('important'),
            ]);
        } else {
            $res = $threads->insert([
                'title' => Post::get('title'),
                'board_id' => Post::get('board_id'),
                'userid' => Session::get('user')->userid,
                'category' => $category,
                'important' => 0,
            ]);
        }
        $view = new View(false);
        if ($res !== false) {
            $postRes = $posts->post(['content' => Post::get('content'), 'thread_id' => $res, 'userid' => Session::get('user')->userid]);
            $view->setStatus($postRes !== false);
            $view->addData(['id' => $res]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function postReply()
    {
        $posts = new Posts();
        $res = $posts->post(Post::get());
        $view = new View(false);
        if ($res !== false) {
            $view->setStatus(true);
            $res['content'] = $this->quote(htmlspecialchars_decode($res['content']));
            $view->addData(['post' => $res]);
        }
        $view->sendResponse();
    }

    private function loadView($res)
    {
        $view = new View($res !== false);
        if ($res !== false) {
            $view->addData(['result' => $res]);
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
        $this->loadView($res);
    }

    /**
     *
     */
    private function loadBoards()
    {
        $boards = new Boards();
        $res = $boards->loadBoards(Post::get('id'));
        $this->loadView($res);
    }

    /**
     *
     */
    private function editForum()
    {
        $forums = new Forums();
        $res = $forums->update(['title' => Post::get('title'), 'description' => Post::get('description'), 'board_permissions' => (Post::get('board_permissions') !== null) ? Post::get('board_permissions') : 0], $forums->getAdapter()->quoteInto('id=?', Post::get('forum_id')));
        $view = new View($res !== false && $res > 0);
        $view->sendResponse();
    }

    /**
     *
     */
    private function editBoard()
    {
        $boards = new Boards();
        $res = $boards->update(['title' => Post::get('title'), 'description' => Post::get('description')], $boards->getAdapter()->quoteInto('id=?', Post::get('board_id')));
        $view = new View($res !== false && $res > 0);
        $view->sendResponse();
    }

    /**
     *
     */
    private function deletePost()
    {
        $posts = new Posts();
        $data = $posts->getPost(Post::get('id'));
        $view = new View(false);
        if (UserHelper::isAdmin() || $data['userid'] == Session::get('user')->userid) {
            $view->setStatus($posts->deletePost(Post::get('id')));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function editThread()
    {
        $threads = new Threads();
        if (Post::get('important') !== null && UserHelper::isAdmin()) {
            $res = $threads->update([
                'title' => Post::get('title'),
                'category' => Post::get('category'),
                'important' => Post::get('important'),
            ], $threads->getAdapter()->quoteInto('id=?', Post::get('thread_id')));
        } else {
            $res = $threads->update([
                'title' => Post::get('title'),
                'category' => Post::get('category'),
                'important' => 0,
            ], $threads->getAdapter()->quoteInto('id=?', Post::get('thread_id')));
        }
        $view = new View($res !== false);
        $view->sendResponse();
    }
}
