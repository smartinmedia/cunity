<?php
namespace Admin\View;

use Admin\View\Abstractables\View;

/**
 * Class Settings
 * @package Admin\View
 */
class Settings extends View
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
        $this->_templateFile = "settings/" . $_GET['x'] . ".tpl";
        $this->registerCss("settings", $_GET['x']);
    }
}


