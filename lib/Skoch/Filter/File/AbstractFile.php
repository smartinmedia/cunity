<?php

namespace Skoch\Filter\File;

use Skoch\Filter\File\Adapter\AbstractAdapter;
use Zend_Config;
use Zend_Filter_Exception;

/**
 * Class AbstractFile
 * @package Skoch\Filter\File
 */
abstract class AbstractFile
{
    /**
     * @throws Zend_Filter_Exception
     * @param param array|Zend_Config $options
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } elseif (!is_array($options)) {
            throw new Zend_Filter_Exception(
                'Invalid options argument provided to filter'
            );
        }

        return $options;
    }

    /**
     * @param $options
     */
    protected function evaluateAdapter($options)
    {
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
    }
}
