<?php

namespace Core;

use Core\Models\Db\Table\Modules;

/**
 * Class Module
 * @package Core
 */
class Module
{

    /**
     * @var
     */
    protected $_tag;
    /**
     * @var null|\Zend_Db_Table_Row_Abstract
     */
    private $_data;

    /**
     * @param $moduletag
     */
    public function __construct($moduletag)
    {
        $this->_tag = $moduletag;
        if (!class_exists($this->getClassName()))
            new View\PageNotFound;
        else {
            $modules = new Modules();
            $this->_data = $modules->getModuleData($this->_tag);
        }
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return ucfirst($this->_tag) . "\Controller";
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return (class_exists($this->getClassName()) &&
            in_array(
                'Core\ModuleController',
                class_implements($this->getClassName())
            )
        );
    }

    /**
     * @return bool|string
     */
    public function isActive()
    {
        if ($this->_data !== NULL)
            return $this->_data['status'];
        else
            return true;
    }

}
