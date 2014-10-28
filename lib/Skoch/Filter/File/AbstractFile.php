<?php

namespace Skoch\Filter\File;

use Zend_Config;
use Zend_Filter_Exception;

/**
 * Class AbstractFile
 * @package Skoch\Filter\File
 */
abstract class AbstractFile
{
    /**
     * @param array $options
     * @throws Zend_Filter_Exception
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

        return $options;
    }
}
