<?php

namespace Admin;

use Core\Models\Request;
use Core\ModuleController;
use Register\Models\Login;

/**
 * Class Controller
 * @package Admin
 */
class Controller implements ModuleController {

    /**
     *
     */
    public function __construct() {
        if (isset($_GET['action']) && $_GET['action'] != "update") {
            Login::loginRequired();
        }
        if (isset($_GET['action']) && $_GET['action'] == "login") {
            new Models\Login("login");
        } else if (isset($_GET['action']) && $_GET['action'] == "save") {
            new Models\Process($_POST['form']);
        } else if (isset($_GET['action']) && $_GET['action'] == "update") {
            new Models\Updater\DatabaseUpdater();
        } else if (isset($_GET['action']) && !empty($_GET['action'])) {
            $model = "\Admin\Models\\Pages\\" . ucfirst($_GET['action']);
            if (!Models\Login::loggedIn())
                new View\Login();
            else if (Request::isAjaxRequest())
                new $model;
        } else
            new View\Admin();
    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onRegister($user) {
        
    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onUnregister($user) {
        
    }

}
