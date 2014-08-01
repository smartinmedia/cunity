<?php

namespace Start\View;

use Core\View\View;

/**
 * Class Startpage
 * @package Start\View
 */
class Startpage extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "start";
    /**
     * @var string
     */
    protected $_templateFile = "startpage.tpl";
    /**
     * @var string
     */
    protected $_languageFolder = "start/languages";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Welcome"];
    /**
     * @var bool
     */
    protected $_useWrapper = false;

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->assign('success', false);
        $this->registerScript("register", "registration");
        $this->registerCss("register", "style");
        $this->registerCunityPlugin(
            "bootstrap-datepicker",
            ["css/bootstrap-datepicker.css", "js/bootstrap-datepicker.js"]
        );
        $this->render();
    }

    /**
     *
     */
    public function render()
    {
        $this->show();
    }

}
