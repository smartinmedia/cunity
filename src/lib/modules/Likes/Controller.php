<?php

namespace Likes;

use Core\ModuleController;
use Register\Models\Login;

/**
 * Class Controller
 * @package Likes
 */
class Controller implements ModuleController
{

    /**
     *
     */
    public function __construct()
    {
        Login::loginRequired();
        new Models\Likes($_GET['action']);
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
