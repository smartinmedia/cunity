<?php

namespace Search\View;

use Core\View\View;

/**
 * Class Searchresults
 * @package Search\View
 */
class Searchresults extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "search";
    /**
     * @var string
     */
    protected $_templateFile = "searchresults.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Searchresults"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerScript("search", "searchresults");
        $this->assign("queryString", $_GET['q']);
        $this->show();
    }

    /**
     * @param array $data
     */
    public function setMetaData(array $data)
    {
        $this->_metadata = $data;
    }

}
