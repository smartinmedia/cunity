<?php

namespace Forums\View;

use Core\View\View;

/**
 * Class Forums
 * @package Forums\View
 */
class Forums extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "forums";
    /**
     * @var string
     */
    protected $_templateFile = "forums.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Forums"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCss("forums", "forums");
        $this->registerScript("forums", "forums");
        $this->registerScript("forums", "category-cloud");
        $this->show();
    }

}
 