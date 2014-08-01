<?php
namespace Gallery\View;

use Core\View\View;

/**
 * Class Album
 * @package Gallery\View
 */
class Album extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "gallery";
    /**
     * @var string
     */
    protected $_templateFile = "album.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Album"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCss("gallery", "album");
        $this->registerCss("gallery", "lightbox");
        $this->registerScript("gallery", "uploader");
        $this->registerScript("gallery", "jquery.blueimp-gallery");
        $this->registerScript("gallery", "album");
        $this->registerScript("gallery", "lightbox");
        $this->registerCunityPlugin("plupload",["js/plupload.full.min.js"]);
    }
}