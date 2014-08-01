<?php

namespace Forums\View;

use Core\View\View;

/**
 * Class Thread
 * @package Forums\View
 */
class Thread extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "forums";
    /**
     * @var string
     */
    protected $_templateFile = "thread.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Topic"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCss("forums", "thread");
        $this->registerScript("forums", "thread");
        $this->registerCunityPlugin(
            "summernote",
            [
            "css/summernote.css",
            "js/summernote.min.js"
            ]
        );
        $this->registerScript("forums", "category-cloud");
    }

}
