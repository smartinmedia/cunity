<?php

namespace Friends;

use Core\ModuleController;
use Register\Models\Login;

/**
 * Class Controller
 * @package Friends
 */
class Controller implements ModuleController
{

    /**
     * @var array
     */
    private $_allowedActions = [
        "add",
        "block",
        "confirm",
        "remove",
        "change",
        "loadData",
        "load",
        "loadOnline",
        "chatStatus"
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
            new View\FriendList();
        elseif (
            isset(
                $_GET['action']
            ) &&
            !empty(
            $_GET['action']
            ) &&
            in_array(
                $_GET['action'],
                $this->_allowedActions
            )
        )
            new Models\Process($_GET['action']);
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
