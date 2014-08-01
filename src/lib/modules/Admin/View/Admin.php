<?php

namespace Admin\View;

use Admin\Models\Login;
use Core\View\View;

/**
 * Class Admin
 * @package Admin\View
 */
class Admin extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "";
    /**
     * @var string
     */
    protected $_templateFile = "";
    /**
     * @var string
     */
    protected $_wrapper = "Admin/styles/out_wrap.tpl";

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        Login::loginRequired();

        $this->show();
    }

}
