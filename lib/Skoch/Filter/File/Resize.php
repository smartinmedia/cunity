<?php

/**
 * Zend Framework addition by skoch
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
class Resize implements \Zend_Filter_Interface
{

    /**
     * @var null
     */
    protected $_width = null;
    /**
     * @var null
     */
    protected $_height = null;
    /**
     * @var bool
     */
    protected $_keepRatio = true;
    /**
     * @var bool
     */
    protected $_keepSmaller = true;
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
     * @return \Skoch\Filter\File\Resize An instance of this filter
     */
    public function __construct($options = [])
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (!is_array($options)) {
            throw new Zend_Filter_Exception(
                'Invalid options argument provided to filter'
            );
        }

        if (!isset($options['width']) && !isset($options['height'])) {
            throw new Zend_Filter_Exception(
                'At least one of width or height must be defined'
            );
        }

        if (isset($options['width'])) {
            $this->_width = $options['width'];
        }
        if (isset($options['height'])) {
            $this->_height = $options['height'];
        }
        if (isset($options['keepRatio'])) {
            $this->_keepRatio = $options['keepRatio'];
        }
        if (isset($options['keepSmaller'])) {
            $this->_keepSmaller = $options['keepSmaller'];
        }
        if (isset($options['directory'])) {
            $this->_directory = $options['directory'];
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
     * @return string|bool Filename or false when there were errors
     */
    public function filter($value)
    {
        if ($this->_directory) {
            $target = $this->_directory . '/' . basename($value);
        } else {
            $target = $value;
        }

        return $this->_adapter->resize(
            $this->_width,
            $this->_height,
            $this->_keepRatio,
            $value,
            $target,
            $this->_keepSmalle
        );
    }

}
