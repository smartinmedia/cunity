<?php

namespace Cunity\Filesharing\Helper;

use Cunity\Core\Cunity;
use Cunity\Core\Exceptions\DirectoryNotWriteable;
use Cunity\Core\Models\Db\Table\Settings;
use Cunity\Core\Request\Session;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class UploadHelper.
 */
class UploadHelper
{
    /**
     * @var string
     */
    protected $destinationFilename;

    /**
     * @var string
     */
    protected $destinationPath;

    /**
     * @var string
     */
    protected $tempFile;

    /**
     * @var \Zend_Config_Xml
     */
    protected $configuration;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     *
     */
    public function __construct()
    {
        $this->settings = Cunity::get('settings');
        $this->configuration = Cunity::get('config');
    }

    /**
     * @return bool
     *
     * @throws DirectoryNotWriteable
     */
    public function upload()
    {
        $this->prepareFiles();

        if (!is_writable($this->destinationPath)) {
            throw new DirectoryNotWriteable();
        }

        return move_uploaded_file($this->tempFile, $this->destinationPath.$this->destinationFilename);
    }

    /**
     * @param string $destinationPath
     *
     * @return $this
     */
    public function setDestinationPath($destinationPath)
    {
        $this->destinationPath = $destinationPath;

        return $this;
    }

    /**
     * @param string $destinationFilename
     *
     * @return $this
     */
    public function setDestinationFilename($destinationFilename)
    {
        $this->destinationFilename = $destinationFilename;

        return $this;
    }

    /**
     * @param string $tempFile
     *
     * @return $this
     */
    public function setTempFile($tempFile)
    {
        $this->tempFile = $tempFile;

        return $this;
    }

    /**
     *
     */
    protected function createPath()
    {
        if (!is_dir($this->destinationPath)) {
            mkdir($this->destinationPath, 0755, true);
        }
    }

    /**
     *
     */
    protected function createDestinationPath()
    {
        if (null === $this->destinationPath) {
            $this->destinationPath = __DIR__.'/../../../../data/uploads/files/'.Session::get('user')->userid.'/';
        }
    }

    /**
     *
     */
    protected function createDestinationFilename()
    {
        $pathinfo = pathinfo($this->tempFile);

        if (null === $this->destinationFilename) {
            $this->destinationFilename = md5(microtime()).'.'.$pathinfo['extension'];
        }
    }

    /**
     *
     */
    protected function checkTempFile()
    {
        if (!file_exists($this->tempFile)) {
            throw new FileNotFoundException();
        }
    }

    /**
     *
     */
    protected function prepareFiles()
    {
        $this->checkTempFile();
        $this->createDestinationFilename();
        $this->createDestinationPath();
        $this->createPath();
    }
}
