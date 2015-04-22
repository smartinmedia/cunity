<?php

/**
 * Zend Framework addition by skoch.
 *
 * @category   Skoch
 *
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author     Stefan Koch <cct@stefan-koch.name>
 */
/**
 * @see Zend_Filter_Interface
 */
namespace Skoch\Filter\File;

use Skoch\Filter\File\Adapter\AbstractAdapter;
use Zend_Config;
use Zend_Filter_Exception;

/**
 * Resizes a given file and saves the created file.
 *
 * @category   Skoch
 */
class Resize extends AbstractFile implements \Zend_Filter_Interface
{
    /**
     * @var null
     */
    protected $width = null;
    /**
     * @var null
     */
    protected $height = null;
    /**
     * @var bool
     */
    protected $keepRatio = true;
    /**
     * @var bool
     */
    protected $keepSmaller = true;
    /**
     * @var null
     */
    protected $directory = null;
    /**
     * @var string
     */
    protected $adapter = 'Skoch\Filter\File\Adapter\Gd';

    /**
     * Create a new resize filter with the given options.
     *
     * @param Zend_Config|array $options Some options. You may specify: width,
     *                                   height, keepRatio, keepSmaller (do not resize image if it is smaller than
     *                                   expected), directory (save thumbnail to another directory),
     *                                   adapter (the name or an instance of the desired adapter)
     *
     * @throws Zend_Filter_Exception
     *
     * @return \Skoch\Filter\File\Resize An instance of this filter
     */
    public function __construct($options)
    {
        $options = parent::__construct($options);

        if (!isset($options['width']) && !isset($options['height'])) {
            throw new Zend_Filter_Exception(
                'At least one of width or height must be defined'
            );
        }

        if (isset($options['width'])) {
            $this->width = $options['width'];
        }
        if (isset($options['height'])) {
            $this->height = $options['height'];
        }
        if (isset($options['keepRatio'])) {
            $this->keepRatio = $options['keepRatio'];
        }
        if (isset($options['keepSmaller'])) {
            $this->keepSmaller = $options['keepSmaller'];
        }
        if (isset($options['directory'])) {
            $this->directory = $options['directory'];
        }

        $this->evaluateAdapter($options);
        $this->prepareAdapter();
    }

    /**
     * Instantiate the adapter if it is not already an instance.
     */
    protected function prepareAdapter()
    {
        if ($this->adapter instanceof AbstractAdapter) {
            return;
        } else {
            $this->adapter = new $this->adapter();
        }
    }

    /**
     * Defined by Zend_Filter_Interface.
     *
     * Resizes the file $value according to the defined settings
     *
     * @param string $value Full path of file to change
     *
     * @return string|bool Filename or false when there were errors
     */
    public function filter($value)
    {
        if ($this->directory) {
            $target = $this->directory.'/'.basename($value);
        } else {
            $target = $value;
        }

        return $this->adapter->resize(
            $this->width,
            $this->height,
            $this->keepRatio,
            $value,
            $target,
            $this->_keepSmalle
        );
    }
}
