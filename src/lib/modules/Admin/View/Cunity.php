<?php

namespace Admin\View;

use Admin\View\Abstractables\View;

/**
 * Class Cunity
 * @package Admin\View
 */
class Cunity extends View
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
        $this->_templateFile = "cunity/" . $_GET['x'] . ".tpl";
        $this->registerCss("cunity", $_GET['x']);
    }

}
