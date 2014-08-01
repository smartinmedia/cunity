<?php

namespace Friends\Models;
use Core\Exception;
use Core\View\Ajax\View;

/**
 * Class FriendShipController
 * @package Friends\Models
 */
class FriendShipController {

    /**
     *
     */
    public function __construct() {
        if (method_exists($this, $_GET['action'])) {
            call_user_func([$this, $_GET['action']]);
        }
    }

    /**
     *
     */
    private function add() {
        if (!isset($_POST['userid']))
            new Exception("No userid given!");
        else {
            $relations = new Db\Table\Relationships();
            $res = $relations->insert(["sender" => $_SESSION['user']->userid, "receiver" => $_POST['userid'], "status" => 1]);
            if ($res) {
                $view = new View($res !== false);
                $view->sendResponse();
            }
        }
    }

    /**
     *
     */
    private function block() {
        if (!isset($_POST['userid']))
            new Exception("No userid given!");
        else {
            $relations = new Db\Table\Relationships();
            $res = $relations->updateRelation($_SESSION['user']->userid, $_POST['userid'], ["status" => 0, "sender" => $_SESSION['user']->userid, "receiver" => $_POST['userid']]);
            if ($res) {
                $view = new View($res !== false);
                $view->sendResponse();
            }
        }
    }

    /**
     *
     */
    private function confirm() {
        if (!isset($_POST['userid'])) // Here the userid is the relation id to make it easier to identify the friendship!
            new Exception("No userid given!");
        else {
            $relations = new Db\Table\Relationships();
            $res = $relations->updateRelation($_SESSION['user']->userid, $_POST['userid'], ["status" => 2]);
            if ($res) {
                $view = new View($res !== false);
                $view->sendResponse();
            }
        }
    }

    /**
     *
     */
    private function remove() {
        if (!isset($_POST['userid'])) // Here the userid is the relation id to make it easier to identify the friendship!
            new Exception("No userid given!");
        else {
            $relations = new Db\Table\Relationships();
            $res = $relations->deleteRelation($_SESSION['user']->userid, $_POST['userid']);
            if ($res) {
                $view = new View($res !== false);
                $view->sendResponse();
            }
        }
    }

    /**
     *
     */
    private function change() {
        if (!isset($_POST['userid'])) // Here the userid is the relation id to make it easier to identify the friendship!
            new Exception("No userid given!");
        else {
            $relations = new Db\Table\Relationships();
            $res = $relations->updateRelation($_POST['userid'], $_SESSION['user']->userid, ["status" => $_POST['status']]);
            if ($res) {
                $view = new View($res !== false);
                $view->sendResponse();
            }
        }
    }

    /**
     * @throws Exception
     */
    private function loadData() {
        $userid = $_POST['userid'];
        $users = $_SESSION['user']->getTable();
        $result = $users->get($userid);
        if ($result === NULL)
            throw new Exception("No User found with the given ID!");
        else {
            $view = new View(true);
            $view->addData(["user" => $result->toArray(["pimg", "username", "firstname", "lastname"])]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function load() {
        $relations = new Db\Table\Relationships();
        $userid = ($_POST['userid'] == 0) ? $_SESSION['user']->userid : $_POST['userid'];
        $rows = $relations->getFullFriendList(">1", $userid);
        $view = new View(true);
        $view->addData(["result" => $rows]);
        $view->sendResponse();
    }

}
