<?php

namespace Admin\View;

use Admin\View\Abstractables\View;

/**
 * Class Appearance
 * @package Admin\View
 */
class Appearance extends View
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
        $this->_templateFile = "appearance/" . $_GET['x'] . ".tpl";
        $this->registerCss("appearance", $_GET['x']);
    }

}
