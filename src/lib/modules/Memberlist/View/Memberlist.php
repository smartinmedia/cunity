<?php

namespace Memberlist\View;

use Core\View\View;

/**
 * Class Memberlist
 * @package Memberlist\View
 */
class Memberlist extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "memberlist";
    /**
     * @var string
     */
    protected $_templateFile = "memberlist.tpl";
    /**
     * @var string
     */
    protected $_languageFolder = "Memberlist/languages";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Memberlist"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerScript("memberlist", "memberlist");
        $this->show();
    }

}
