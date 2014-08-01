<?php

namespace Notifications\Models;
use Core\View\Ajax\View;
use Friends\Models\Db\Table\Relationships;
use Notifications\Models\Db\Table\Notifications;

/**
 * Class Process
 * @package Notifications\Models
 */
class Process {

    public function get() {
        $n = new \Notifications\Models\Db\Table\Notifications();
        $res = $n->getNotifications();
        $view = new \Core\View\Ajax\View(true);
        $view->addData(["result" => $res["all"], "new" => $res["new"]]);
        $view->sendResponse();
    }

}
