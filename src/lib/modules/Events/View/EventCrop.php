<?php

namespace Events\View;

use Core\View\View;

/**
 * Class EventCrop
 * @package Events\View
 */
class EventCrop extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "events";
    /**
     * @var string
     */
    protected $_templateFile = "event-crop.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Crop Image"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerScript("profile", "jquery.imgareaselect.pack");
        $this->registerScript("events", "event-crop");
        $this->registerCss("events", "event");
        $this->registerCss("profile", "imgareaselect-animated");
        $this->registerCss("events", "event-crop");
        $this->assign("eventid", $_GET['y']);
    }

}
