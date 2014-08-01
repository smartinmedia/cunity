<?php

namespace Forums\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Posts
 * @package Forums\Models\Db\Table
 */
class Posts extends Table {

    /**
     * @var string
     */
    protected $_name = 'forums_posts';
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
     * @param $thread_id
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function loadPosts($thread_id, $limit = 20, $offset = 0) {
        $query = $this->getAdapter()->select()->from(["p" => $this->_name])
                ->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid=.p.userid", ["name", "username"])
                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id=u.profileImage", ["filename"])
                ->joinLeft(["pc" => $this->_dbprefix . "forums_posts"], "pc.thread_id=p.thread_id", new \Zend_Db_Expr("COUNT(DISTINCT pc.id) AS postcount"))
                ->where("p.thread_id=?", $thread_id)
                ->group("p.id")
                ->order("time")
                ->limit($limit, $offset);
        return $this->getAdapter()->fetchAll($query);
    }

    /**
     * @param $postid
     * @return mixed
     */
    public function getPost($postid) {
        $query = $this->getAdapter()->select()->from(["p" => $this->_name])
                ->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid=.p.userid", ["name", "username"])
                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id=u.profileImage", ["filename"])
                ->joinLeft(["pc" => $this->_dbprefix . "forums_posts"], "pc.thread_id=p.thread_id", new \Zend_Db_Expr("COUNT(DISTINCT pc.id) AS postcount"))
                ->where("p.id=?", $postid);
        return $this->getAdapter()->fetchRow($query);
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function post(array $data) {
        $res = $this->insert(array_merge($data, ["userid" => $_SESSION['user']->userid]));
        if ($res !== false)
            return $this->getPost($res);
        return false;
    }

    /**
     * @param $postid
     * @return bool
     */
    public function deletePost($postid) {
        return ($this->delete($this->getAdapter()->quoteInto("id=?", $postid)) > 0);
    }

}
