<?php

namespace Admin\Models\Updater;

use Core\Exception;
use Core\View\Message;

/**
 * Class DatabaseUpdater
 * @package Admin\Models\Updater
 */
class DatabaseUpdater {

    /**
     *
     * @var String
     */
    protected $_directory = "../data/temp/databaseUpdates";
    
    /**
     *
     * @var array
     */
    protected $versions = [];
    
    /**
     *
     * @var Admin\Models\Db\Table\Versions
     */
    protected $versionDb = null;

    /**
     * 
     */
    public function __construct() {
        $this->init();
        $this->run();
    }

    /**
     * 
     */
    private function init() {   
        $this->versionDb = new \Admin\Models\Db\Table\Versions;
        $v = $this->versionDb->getVersions();
        $this->versions = ($v !== false) ? $v : [];
    }

    /**
     * 
     * @param double $timestamp
     * @return boolean
     */
    protected function versionInstalled($timestamp) {
        return in_array($timestamp, $this->versions);
    }

    /**
     * 
     * @throws \Core\Exception
     */
    public function run() {
        $dir = new \DirectoryIterator($this->_directory);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isReadable()) {
                if ($fileinfo->getExtension() == "php") {
                    include_once $this->_directory . DIRECTORY_SEPARATOR . $fileinfo->getFilename();
                    $classname = $fileinfo->getBasename(".php");
                    $classnameParts = explode('_',$classname);
                    if (class_exists($classname) && !$this->versionInstalled($classnameParts[1])) {
                        $dbCmd = new $classname(\Core\Cunity::get("db"));
                        if ($dbCmd instanceof DbCommandInterface) {
                            $dbCmd->execute();
                            $dbCmd->updateDatabaseTimestamp($this->versionDb);
                        }
                    }
                }
            }
        }
        new Message("Done!","The Database updated has finished!","success");
    }

}
