<?php

namespace Register\View;

use Core\View\View;

/**
 * Class Registration
 * @package Register\View
 */
class Registration extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "register";
    /**
     * @var string
     */
    protected $_templateFile = "registration.tpl";
    /**
     * @var string
     */
    protected $_languageFolder = "Register/languages/";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Registration"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerScript("register", "registration");
        $this->registerCss("register", "style");
        $this->registerCunityPlugin(
            "bootstrap-datepicker",
            ["css/bootstrap-datepicker.css", "js/bootstrap-datepicker.js"]
        );
        $this->assign('success', false);
    }

    /**
     *
     */
    public function render()
    {
        $this->show();
    }

}
