<?php

namespace Events;

use Core\ModuleController;
use Register\Models\Login;

/**
 * Class Controller
 * @package Events
 */
class Controller implements ModuleController
{

    /**
     * @var array
     */
    private $_allowedActions = [
        "createEvent",
        "loadEvents",
        "changeStatus",
        "invite",
        "edit",
        "cropImage",
        "crop",
        "loadGuestList"
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
            new View\Events();
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
        else
            new Models\Process("loadEvent");
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
