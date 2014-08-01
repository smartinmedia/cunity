<?php

namespace Core\View;

/**
 * Class PageNotFound
 * @package Core\View
 */
class PageNotFound extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "Core";
    /**
     * @var string
     */
    protected $_templateFile = "404.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "404 - Page Not Found"];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->show();
        exit();
    }

}
