<?php

namespace Admin\View;

use Admin\View\Abstractables\View;

/**
 * Class Mailing
 * @package Admin\View
 */
class Mailing extends View
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
        $this->_templateFile = "mailing/" . $_GET['x'] . ".tpl";
        $this->registerCss("mailing", $_GET['x']);
        $this->show();
    }

}
