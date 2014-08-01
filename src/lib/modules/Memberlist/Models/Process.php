<?php

namespace Memberlist\Models;
use Core\Models\Db\Table\Users;
use Core\View\Ajax\View;

/**
 * Class Process
 * @package Memberlist\Models
 */
class Process {

    /**
     *
     */
    public function getAll() {
        $table = new Users();

        $result = $table->getSet([], "userid", ["username", "name","userid"]);
        $view = new View($result !== NULL);
        $view->addData(["result" => $result->toArray()]);
        $view->sendResponse(); 
    }

}
