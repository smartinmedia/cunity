<?php

namespace Events\View;

use Core\Cunity;
use Core\View\View;

/**
 * Class EventEdit
 * @package Events\View
 */
class EventEdit extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "events";
    /**
     * @var string
     */
    protected $_templateFile = "event-edit.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Edit Event"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCss("events", "event");
        $this->registerCss("events", "event-edit");
        $this->registerScript("events", "event-edit");
        $this->assign("max_filesize", ini_get('upload_max_filesize'));
        $this->assign(
            "upload_limit",
            Cunity::get("config")->site->upload_limit
        );
    }

}
