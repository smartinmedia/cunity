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
    protected $_x = null;
    /**
     * @var null
     */
    protected $_y = null;
    /**
     * @var null
     */
    protected $_x1 = null;
    /**
     * @var null
     */
    protected $_y1 = null;
    /**
     * @var null
     */
    protected $_prefix = null;
    /**
     * @var null
     */
    protected $_thumbsize = null;
    /**
     * @var null
     */
    protected $_directory = null;
    /**
     * @var string
     */
    protected $_adapter = 'Skoch\Filter\File\Adapter\Gd';

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
            $this->_x1 = $options['x1'];
        }
        if (isset($options['y1'])) {
            $this->_y1 = $options['y1'];
        }
        if (isset($options['x'])) {
            $this->_x = $options['x'];
        }
        if (isset($options['y'])) {
            $this->_y = $options['y'];
        }
        if (isset($options['thumbwidth'])) {
            $this->_thumbwidth = $options['thumbwidth'];
        }
        if (isset($options['directory'])) {
            $this->_directory = $options['directory'];
        }
        if (isset($options['prefix'])) {
            $this->_prefix = $options['prefix'];
        }
        if (isset($options['adapter'])) {
            if ($options['adapter'] instanceof
                AbstractAdapter
            ) {
                $this->_adapter = $options['adapter'];
            } else {
                $name = $options['adapter'];
                if (substr($name, 0, 26) != 'Skoch_Filter_File_Adapter_') {
                    $name = 'Skoch_Filter_File_Adapter_'
                        . ucfirst(
                            strtolower($name)
                        );
                }
                $this->_adapter = $name;
            }
        }

        $this->prepareAdapter();
    }

    /**
     * Instantiate the adapter if it is not already an instance
     *
     * @return void
     */
    protected function prepareAdapter()
    {
        if ($this->_adapter instanceof AbstractAdapter) {
            return;
        } else {
            $this->_adapter = new $this->_adapter();
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
        if ($this->_directory) {
            $target = $this->_directory
                . '/'
                . $this->_prefix
                . basename($value);
        } else {
            $target = $this->_prefix . $value;
        }

        if ($this->_thumbwidth == "thumbnail")
            return $this->_adapter->thumbnail($value, $target, 180);
        return $this->_adapter->crop(
            $this->_x,
            $this->_y,
            $this->_x1,
            $this->_y1,
            $value,
            $target,
            $this->_thumbwidth
        );
    }
}
