<?php

namespace Forums;

use Core\ModuleController;
use Register\Models\Login;

/**
 * Class Controller
 * @package Forums
 */
class Controller implements ModuleController
{

    /**
     * @var array
     */
    private $_allowedActions = [
        "forum",
        "board",
        "thread",
        "category",
        "loadForums",
        "loadBoards",
        "loadThreads",
        "loadPosts",
        "loadCategories",
        "createForum",
        "createBoard",
        "startThread",
        "postReply",
        "editForum",
        "editBoard",
        "editThread",
        "deletePost",
        "deleteForum",
        "deleteBoard",
        "deleteThread"
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
            new View\Forums();
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
            new Models\Process("loadTopic");
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
