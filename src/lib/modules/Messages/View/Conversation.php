<?php

namespace Messages\View;

use Core\View\View;

/**
 * Class Conversation
 * @package Messages\View
 */
class Conversation extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "messages";
    /**
     * @var string
     */
    protected $_templateFile = "conversation.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Conversation"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerScript("messages", "conversation");
        $this->registerCss("messages", "conversation");
    }

    /**
     *
     */
    public function render()
    {
        $this->show();
    }

}
