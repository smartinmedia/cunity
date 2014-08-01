<?php

namespace Forums\View;

use Core\View\View;

/**
 * Class Category
 * @package Forums\View
 */
class Category extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "forums";
    /**
     * @var string
     */
    protected $_templateFile = "category.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Category"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCss("forums", "board");
        $this->registerScript("forums", "category");
        $this->registerScript("forums", "category-cloud");
    }

}
