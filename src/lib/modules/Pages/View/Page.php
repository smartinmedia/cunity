<?php
namespace Pages\View;

use Core\View\View;

/**
 * Class Page
 * @package Pages\View
 */
class Page extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "pages";
    /**
     * @var string
     */
    protected $_templateFile = "page.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Content page"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCss("pages", "page");
    }

    /**
     * @param array $data
     */
    public function setMetaData(array $data)
    {
        $this->_metadata = $data;
    }
}


