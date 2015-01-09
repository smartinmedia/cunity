<?php

/**
 * Zend Framework addition by skoch
 * Edited by Julian Seibert for cropping images
 *
 * @category   Skoch
 * @package    Skoch_Filter
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
 * Resizes a given file and saves the created file
 *
 * @category   Skoch
 * @package    Skoch_Filter
 */
class Crop extends AbstractFile implements \Zend_Filter_Interface
{

    /**
     * @var null
     */
    protected $xValue = null;
    /**
     * @var null
     */
    protected $yValue = null;
    /**
     * @var null
     */
    protected $xValue1 = null;
    /**
     * @var null
     */
    protected $yValue1 = null;
    /**
     * @var null
     */
    protected $prefix = null;
    /**
     * @var null
     */
    protected $thumbsize = null;
    /**
     * @var null
     */
    protected $directory = null;
    /**
     * @var AbstractAdapter
     */
    protected $adapter = 'Skoch\Filter\File\Adapter\Gd';

    /**
     * Create a new resize filter with the given options
     *
     * @param Zend_Config|array $options Some options. You may specify: width,
     * height, keepRatio, keepSmaller (do not resize image if it is smaller than
     * expected), directory (save thumbnail to another directory),
     * adapter (the name or an instance of the desired adapter)
     * @throws Zend_Filter_Exception
     * @return \Skoch\Filter\File\Crop An instance of this filter
     */
    public function __construct($options)
    {
        $options = parent::__construct($options);

        if (isset($options['x1'])) {
            $this->xValue1 = $options['x1'];
        }
        if (isset($options['y1'])) {
            $this->yValue1 = $options['y1'];
        }
        if (isset($options['x'])) {
            $this->xValue = $options['x'];
        }
        if (isset($options['y'])) {
            $this->yValue = $options['y'];
        }
        if (isset($options['thumbwidth'])) {
            $this->_thumbwidth = $options['thumbwidth'];
        }
        if (isset($options['directory'])) {
            $this->directory = $options['directory'];
        }
        if (isset($options['prefix'])) {
            $this->prefix = $options['prefix'];
        }
        $this->evaluateAdapter($options);

        $this->prepareAdapter();
    }

    /**
     * Instantiate the adapter if it is not already an instance
     *
     * @return void
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
     * Defined by Zend_Filter_Interface
     *
     * Resizes the file $value according to the defined settings
     *
     * @param  string $value Full path of file to change
     * @return string Filename or false when there were errors
     */
    public function filter($value)
    {
        if ($this->directory) {
            $target = $this->directory
                . '/'
                . $this->prefix
                . basename($value);
        } else {
            $target = $this->prefix . $value;
        }

        if ($this->_thumbwidth == "thumbnail")
            return $this->adapter->thumbnail($value, $target, 180);
        return $this->adapter->crop(
            $this->xValue,
            $this->yValue,
            $this->xValue1,
            $this->yValue1,
            $value,
            $target,
            $this->_thumbwidth
        );
    }
}
