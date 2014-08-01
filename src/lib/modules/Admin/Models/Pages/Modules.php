<?php

namespace Admin\Models\Pages;

use \Core\View\Ajax\View;

/**
 * Class Modules
 * @package Admin\Models\Pages
 */
class Modules extends PageAbstract {

    /**
     *
     */
    public function __construct() {
        if (isset($_POST) && !empty($_POST)) {
            $this->handleRequest();
        } else {
            $this->loadData();
            $this->render("modules");
        }
    }

    private function handleRequest() {
        $view = new View(true);
        switch ($_POST['action']) {
            case 'loadModules':
                $modules = new \Core\Models\Db\Table\Modules();
                $view->addData(["modules" => $modules->getModules()->toArray()]);
                $view->sendResponse();
                break;
        }
    }

    /**
     *
     */
    private function loadData() {
        $modules = new \Core\Models\Db\Table\Modules();
        $installedModules = $modules->getModules()->toArray();
        $this->assignments['installedModules'] = $installedModules;
    }

}
