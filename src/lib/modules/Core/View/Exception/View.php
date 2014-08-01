<?php
namespace Core\View\Exception;

/**
 * Class View
 * @package Core\View\Exception
 */
class View extends \Core\View\View
{
    /**
     * @var string
     */
    protected $_templateDir = "core";
    /**
     * @var string
     */
    protected $_templateFile = "exception.tpl";
    /**
     * @var string
     */
    protected $_languageFolder = "core/languages/";
    /**
     * @var array
     */
    protected $_metadata = [
        "title" => "Error",
        "description" => "Cunity - Your private social network"
    ];

    /**
     * @param $e
     * @throws \Exception
     */
    public function __construct($e)
    {
        parent::__construct();
        $this->assign('MESSAGE', $e->getMessage());
        $this->show();
    }
}


