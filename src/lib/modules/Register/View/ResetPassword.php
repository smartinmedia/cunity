<?php

namespace Register\View;

use Core\View\View;

/**
 * Class ResetPassword
 * @package Register\View
 */
class ResetPassword extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "register";
    /**
     * @var string
     */
    protected $_templateFile = "resetpw.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Reset Password"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCss("register", "resetpw");
    }

}
