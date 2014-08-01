<?php

namespace Friends\Models;

use Core\Exception;
use Friends\Models\Db\Table\Relationships;
use Core\View\Ajax\View;
use Notifications\Models\Notifier;

/**
 * Class Process
 * @package Friends\Models
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
    private function add() {
        if (!isset($_POST['userid']))
            new Exception("No userid given!");
        else {
            $relations = new Relationships();
            $res = $relations->insert(["sender" => $_SESSION['user']->userid, "receiver" => $_POST['userid'], "status" => 1]);
            if ($res) {
                Notifier::notify($_POST['userid'], $_SESSION['user']->userid, "addfriend", "index.php?m=profile&action=" . $_SESSION['user']->username);
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
            $relations = new Relationships();
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
            $relations = new Relationships();
            $res = $relations->updateRelation($_SESSION['user']->userid, $_POST['userid'], ["status" => 2]);
            if ($res) {
                Notifier::notify($_POST['userid'], $_SESSION['user']->userid, "confirmfriend", "index.php?m=profile&action=" . $_SESSION['user']->username);
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
            $relations = new Relationships();
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
            $relations = new Relationships();
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
        $relations = new Relationships();
        $rows = $relations->getFullFriendList(">1", $_POST['userid']);
        $view = new View(true);
        $view->addData(["result" => $rows]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadOnline() {
        $view = new View(false);
        if ($_SESSION['user']->chat_available == 1) {
            $relations = new Relationships();
            $friends = $relations->loadOnlineFriends($_SESSION['user']->userid);
            $view->addData(["result" => $friends]);
            $view->setStatus(true);
        } else {
            $view->setStatus(true);
            $view->addData(["result" => [], "msg" => "disabled"]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function chatStatus() {
        $view = new View(false);
        if ($_POST['status'] == 1 || $_POST['status'] == 0) {
            $_SESSION['user']->chat_available = $_POST['status'];
            $view->setStatus($_SESSION['user']->save() > 0);
        }
        $view->sendResponse();
    }

}
