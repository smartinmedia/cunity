<?php

namespace Newsfeed\Models;
use Core\View\Ajax\View;

/**
 * Class Process
 * @package Newsfeed\Models
 */
class Process {

    /**
     * @param $action
     */
    public function __construct($action) {
        if (method_exists($this, $action))
            call_user_func([$this, $action]);
    }

    /**
     *
     */
    private function send() {
        $view = new View();
        if (empty($_POST['content'])) {
            $view->setStatus(false);
            $view->addData(["msg" => $view->translate("Oups! Your post is empty!")]);
        } else {
            $table = new Db\Table\Posts();
            $videoData = ["video" => json_decode(html_entity_decode($_POST['youtubedata']), true), "content" => $_POST['content']];
            $content = ($_POST['type'] == "video") ? json_encode($videoData) : $_POST['content'];
            $res = $table->post(["userid" => $_SESSION['user']->userid, "wall_owner_id" => $_POST['wall_owner_id'], "wall_owner_type" => $_POST['wall_owner_type'], "privacy" => $_POST['privacy'], "content" => $content, "type" => $_POST['type']]);
            $view->setStatus($res !== false);
            if ($_POST['type'] == "video")
                $view->addData(array_merge($res, ["content" => $videoData]));
            else
                $view->addData($res);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function delete() {
        $table = new Db\Table\Posts();
        $res = $table->deletePost($_POST['id']);
        $view = new View($res);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadPost() {
        $table = new Db\Table\Posts();
        $res = $table->loadPost($_POST['postid']);
        $view = new View($res !== NULL);
        $view->addData($res);
        $view->sendResponse();
    }

    /**
     *
     */
    private function load() {
        if (!isset($_POST['wall_owner_id']) || $_POST['wall_owner_id'] == 0) {
            $newsfeed = new Db\Table\Walls();
            $res = $newsfeed->getNewsfeed($_POST['offset'], $_POST['refresh'], $_POST['filter']);
            $view = new View(true);
            $view->addData(["posts" => $res]);
            $view->sendResponse();
        } else if (isset($_POST['wall_owner_id']) && $_POST['wall_owner_id'] > 0 && isset($_POST['wall_owner_type']) && !empty($_POST['wall_owner_type'])) {
            $newsfeed = new Db\Table\Walls();
            $res = $newsfeed->getWall($_POST['wall_owner_id'], $_POST['wall_owner_type'], $_POST['offset'], $_POST['refresh'], $_POST['filter']);
            $view = new View(true);
            $view->addData(["posts" => $res]);
            $view->sendResponse();
        }
    }

}
