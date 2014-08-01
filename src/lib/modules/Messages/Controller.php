<?php

namespace Messages;

use Core\ModuleController;
use Register\Models\Login;

/**
 * Class Controller
 * @package Messages
 */
class Controller implements ModuleController
{

    /**
     * @var array
     */
    private $_allowedActions = [
        "send",
        "load",
        "loadConversationMessages",
        "deletemessage",
        "startConversation",
        "leaveConversation",
        "loadUnread",
        "invite",
        "chatHearthBeat",
        "getConversation",
        "markAsRead"
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
            new View\Inbox();
        else if (isset($_GET['action']) &&
            !empty($_GET['action']) &&
            in_array($_GET['action'], $this->_allowedActions))
            new Models\Process($_GET['action']);
        else if (isset($_GET['action']) && !empty($_GET['action']))
            new Models\Conversation();
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
