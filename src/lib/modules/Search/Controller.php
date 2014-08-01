<?php

namespace Search;

use Core\ModuleController;
use Core\View\Ajax\View;
use Search\View\Searchresults;

/**
 * Class Controller
 * @package Search
 */
class Controller implements ModuleController
{

    /**
     *
     */
    public function __construct()
    {
        $this->handleRequest();
//        $s = new Models\Process();
//        $s->recreateSearchIndex();
    }

    /**
     *
     */
    private function handleRequest()
    {
        if (isset($_GET['q']) && !empty($_GET['q']) && empty($_GET['action']))
            new Searchresults();
        else if (isset($_GET['action']) && $_GET['action'] == "livesearch") {
            $process = new Models\Process();
            $result = $process->find($_POST['q']);
            $view = new View();
            $view->setStatus(true);
            $view->addData($result);
            $view->sendResponse();
        }
    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onRegister($user)
    {
        $searchindex = new Models\Process();
        $searchindex->addUser($user->username, $user->name);
    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onUnregister($user)
    {
        $searchindex = new Models\Process();
        $searchindex->removeUser($user->username);
    }

}
