<?php

namespace Profile\View;

use Core\View\View;

/**
 * Class ProfileCrop
 * @package Profile\View
 */
class ProfileCrop extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "profile";
    /**
     * @var string
     */
    protected $_templateFile = "profile-crop.tpl";
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
        $this->registerScript("profile", "profile-crop");
        $this->registerCss("profile", "profile");
        $this->registerCss("profile", "imgareaselect-animated");
        $this->registerCss("profile", "profile-crop");
    }
}

