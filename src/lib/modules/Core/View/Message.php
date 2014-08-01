<?php

namespace Core\View;

/**
 * Class Message
 * @package Core\View
 */
class Message extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "core";
    /**
     * @var string
     */
    protected $_templateFile = "message.tpl";
    /**
     * @var array
     */
    private $_validTypes = ["info", "danger", "success"];

    /**
     * @param $header
     * @param $message
     * @param string $type
     * @throws \Exception
     */
    public function __construct($header, $message, $type = "info")
    {
        parent::__construct();
        $titleTranslated = $this->translate($header);
        $this->_metadata = ["title" => $titleTranslated];
        $this->assign("MESSAGE", $this->translate($message));
        $this->assign("HEADER", $titleTranslated);
        $this->assign(
            "TYPE", (
        in_array($type, $this->_validTypes) ? $type : "info")
        );
        $this->show();
    }

}
