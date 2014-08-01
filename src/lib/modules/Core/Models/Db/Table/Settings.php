<?php

namespace Core\Models\Db\Table;

use Core\Exception;
use Core\Models\Db\Abstractables\Table;

/**
 * Class Settings
 * @package Core\Models\Db\Table
 */
class Settings extends Table {

    /**
     * @var string
     */
    protected $_name = 'settings';
    /**
     * @var string
     */
    protected $_primary = 'name';
    /**
     * @var string
     */
    protected $_rowClass = "\Core\Models\Db\Row\Setting";
    /**
     * @var array
     */
    private $settings = [];

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $name
     * @return string
     */
    public function getSetting($name) {
        if (isset($this->settings[$name]))
            return $this->settings[$name];
        $row = $this->fetchRow($this->select()->where("name=?", $name));
        $this->settings[$name] = $row->value;
        return $row->value;
    }

    /**
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function getSettings() {
        return $this->fetchAll();
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function setSetting($name, $value) {        
        $row = $this->fetchRow($this->select()->where("name=?", $name));
        if ($row == NULL)
            throw new Exception("Try to set undefined setting: \"" . $name . "\"");
        else {
            $row->value = $value;
            return (false !== $row->save());
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function __get($name) {
        return $this->getSetting($name);
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function __set($name, $value) {
        return $this->setSetting($name, $value);
    }

}
