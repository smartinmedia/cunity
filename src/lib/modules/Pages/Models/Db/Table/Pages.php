<?php

namespace Pages\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Pages
 * @package Pages\Models\Db\Table
 */
class Pages extends Table {

    /**
     * @var string
     */
    protected $_name = 'pages';
    /**
     * @var string
     */
    protected $_primary = 'shortlink';
    /**
     * @var string
     */
    protected $_rowClass = "\Pages\Models\Db\Row\Page";

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $shortlink
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getPage($shortlink) {
        return $this->fetchRow($this->select()->where("shortlink=?", $shortlink));
    }

    /**
     * @param $id
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getPageById($id) {
        return $this->fetchRow($this->select()->where("id=?", intval($id)));
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function addPage(array $data) {
        $returnValue = false;

        if (isset($data['pageid']) && $data['pageid'] > 0) {
            if (false !== $this->update([
                        "title" => $data['title'],
                        "content" => $data['content'],
                        "comments" => isset($data['comments']) ? 1 : 0,
                        "shortlink" => preg_replace('/[^a-zA-Z0-9\-]/', "", $data['title'])
                            ], "id=" . $data['pageid']))
                $returnValue = preg_replace('/[^a-zA-Z0-9\-]/', "", $data['title']);
        } else {
            $returnValue = $this->insert([
                        "title" => $data['title'],
                        "content" => $data['content'],
                        "comments" => isset($data['comments']) ? 1 : 0,
                        "shortlink" => preg_replace('/[^a-zA-Z0-9\-]/', "", $data['title'])
            ]);
        }

        return $returnValue;
    }

    /**
     * @param $pageid
     * @return bool
     */
    public function deletePage($pageid) {
        return ($this->delete($this->getAdapter()->quoteInto("id=?", $pageid)) > 0);
    }

    /**
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function loadPages() {
        $res = $this->fetchAll();
        foreach ($res AS $page)
            $page->content = html_entity_decode($page->content);

        return $res;
    }

}
