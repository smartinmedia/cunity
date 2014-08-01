<?php

namespace Newsfeed;

use Core\ModuleController;
use Register\Models\Login;

/**
 * Class Controller
 * @package Newsfeed
 */
class Controller implements ModuleController
{

    /**
     * @var array
     */
    private $_allowedActions = [
        "send",
        "load",
        "delete",
        "loadPost"
    ];

    /**
     *
     */
    public function __construct()
    {
        Login::loginRequired();
        $this->handleRequest();
    }

    /**
     *
     */
    private function handleRequest()
    {
        if (!isset($_GET['action']) || empty($_GET['action']))
            new View\Newsfeed();
        else if (
            isset(
                $_GET['action']) &&
                !empty(
                    $_GET['action']
                ) &&
            in_array($_GET['action'], $this->_allowedActions)
        )
            new Models\Process($_GET['action']);
    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onRegister($user)
    {
        $walls = new Models\Db\Table\Walls();
        $walls->createWall($user->userid);
    }

    /**
     * @param $user
     * @return mixed|void
     */
    public static function onUnregister($user)
    {
        $walls = new Models\Db\Table\Walls();
        $walls->deleteWallByOwner($user->userid, "profile");
    }

}
