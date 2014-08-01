<?php

namespace Core\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Menu
 * @package Core\Models\Db\Table
 */
class Menu extends Table {

    /**
     * @var string
     */
    protected $_name = 'menu';

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getMainMenu() {
        $res = $this->fetchAll($this->select()->where("menu='main'")->order("pos"));
        return $res->toArray();
    }

    /**
     * @return array
     */
    public function getFooterMenu() {
        $res = $this->fetchAll($this->select()->where("menu='footer'")->order("pos"));
        return $res->toArray();
    }

    /**
     * 
     * @param array $data
     * @return array
     */
    public function addMenuItem(array $data) {
        $res = $this->insert([
            "type" => $data['type'],
            "menu" => $data['menu'],
            "title" => html_entity_decode($data['title']),
            "content" => $data['content'],
            "iconClass" => $data['iconClass']
        ]);
        return $this->find($res)->current()->toArray();
    }

    /**
     * 
     * @param array $not
     * @return boolean
     */
    public function deleteBut(array $not) {
        return (false !== $this->delete($this->getAdapter()->quoteInto("id NOT IN (?)", $not)));
    }

}
