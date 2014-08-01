<?php

namespace Notifications;

use Core\ModuleController;
use Core\View\Ajax\View;
use Notifications\Models\Db\Table\Notifications;

/**
 * Class Controller
 * @package Notifications
 */
class Controller implements ModuleController
{

    /**
     *
     */
    public function __construct()
    {
        $this->handleRequest();
    }

    /**
     *
     */
    private function handleRequest()
    {
        if (isset($_GET['action']) && $_GET['action'] == "get") {
            $process = new Models\Process();
            $process->get();
        } else if (isset($_GET['action']) && $_GET['action'] == "markRead") {
            $view = new View();
            $n = new Notifications();
            $view->setStatus($n->read($_POST['id']));
            $view->sendResponse();
        }
    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onRegister($user)
    {

    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onUnregister($user)
    {

    }

}
