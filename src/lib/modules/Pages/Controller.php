<?php

namespace Pages;

use Core\ModuleController;
use Core\View\PageNotFound;
use Pages\Models\Db\Table\Pages;

/**
 * Class Controller
 * @package Pages
 */
class Controller implements ModuleController
{

    /**
     *
     */
    public function __construct()
    {
        $pages = new Pages();
        $page = $pages->getPage($_GET['action']);
        if ($page == NULL)
            new PageNotFound();
        $page->displayPage();
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
