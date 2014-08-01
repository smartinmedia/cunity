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

/**
 * Resizes a given file and saves the created file
 *
 * @category   Skoch
 * @package    Skoch_Filter
 */
class Skoch_Filter_File_Crop implements \Zend_Filter_Interface
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
     *
     * @todo julian: check if $_x1 can be renamed to avoid numbers
     */
    protected $_x1 = null;
    /**
     * @var null
     *
     * @todo julian: check if $_x1 can be renamed to avoid numbers
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
    protected $_adapter = 'Skoch_Filter_File_Adapter_Gd';

    /**
     * Create a new resize filter with the given options
     *
     * @param Zend_Config|array $options Some options. You may specify: width,
     * height, keepRatio, keepSmaller (do not resize image if it is smaller than
     * expected), directory (save thumbnail to another directory),
     * adapter (the name or an instance of the desired adapter)
     * @throws Zend_Filter_Exception
     * @return \Skoch_Filter_File_Crop An instance of this filter
     */
    public function __construct($options = [])
    {
        if ($options instanceof \Zend_Config) {
            $options = $options->toArray();
        } elseif (!is_array($options)) {
            /** @noinspection PhpIncludeInspection */
            require_once 'Zend/Filter/Exception.php';
            throw new Zend_Filter_Exception(
                'Invalid options argument provided to filter'
            );
        }

        if (isset($options['x1'])) {
            // @todo julian: check if $this->_x1 an $this->_y1
            // @todo julian: can be renamed to avoid numbers
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
                Skoch_Filter_File_Adapter_Abstract) {
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

        $this->_prepareAdapter();
    }

    /**
     * Instantiate the adapter if it is not already an instance
     *
     * @return void
     */
    protected function _prepareAdapter()
    {
        if ($this->_adapter instanceof Skoch_Filter_File_Adapter_Abstract) {
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
