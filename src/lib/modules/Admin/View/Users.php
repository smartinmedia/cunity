<?php

namespace Admin\View;

use Admin\View\Abstractables\View;

/**
 * Class Users
 * @package Admin\View
 */
class Users extends View
{

    /**
     * @var bool
     */
    protected $_useWrapper = false;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_templateFile = "users/" . $_GET['x'] . ".tpl";
        $this->registerCss("users", $_GET['x']);
    }

}
