<?php

namespace Newsfeed\Models\Db\Table;

use Comments\Models\Db\Table\Comments;
use Core\Models\Db\Abstractables\Table;
use Likes\Models\Db\Table\Likes;

/**
 * Class Posts
 * @package Newsfeed\Models\Db\Table
 */
class Posts extends Table {

    /**
     * @var string
     */
    protected $_name = 'posts';
    /**
     * @var string
     */
    protected $_primary = 'id';
    /**
     * @var \Zend_Db_Expr
     */
    private $friendslistQuery;

    /**
     *
     */
    public function __construct() {
        parent::__construct();
        $this->friendslistQuery = new \Zend_Db_Expr($this->getAdapter()->select()
                        ->from($this->_dbprefix . "relations", new \Zend_Db_Expr("(CASE WHEN sender = " . $_SESSION['user']->userid . " THEN receiver WHEN receiver = " . $_SESSION['user']->userid . " THEN sender END)"))
                        ->where("status > 0")
                        ->where("sender=?", $_SESSION['user']->userid)
                        ->orWhere("receiver=?", $_SESSION['user']->userid));
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function post(array $data) {
        if (isset($data['wall_owner_id']) && isset($data['wall_owner_type']) && !empty($data['wall_owner_id']) && !empty($data['wall_owner_type'])) {
            $walls = new Walls();
            $wallid = $walls->getWallId($data['wall_owner_id'], $data['wall_owner_type']);
        } else
            $wallid = $_POST['wallid'];
        $res = $this->insert(["userid" => $data['userid'], "wall_id" => $wallid, "privacy" => $data['privacy'], "content" => $data['content'], "time" => new \Zend_Db_Expr("UTC_TIMESTAMP()"), "type" => $data['type']]);
        if ($res !== NULL)
            return $this->getPostData($res);
        else
            return false;
    }

    /**
     * @param $postid
     * @return mixed
     */
    private function getPostData($postid) {
        $query = $this->getAdapter()->select()->from(["p" => $this->_dbprefix . "posts"])
                ->join(["u" => $this->_dbprefix . "users"], "u.userid=p.userid", ["name", "username"])
                ->join(["w" => $this->_dbprefix . "walls"], "w.wall_id=p.wall_id")
                ->joinLeft(["img" => $this->_dbprefix . "gallery_images"], "img.id=p.content AND p.type = 'image'", ["filename", "caption", "id AS refid"])
                ->joinLeft(["rus" => $this->_dbprefix . "users"], "rus.userid=w.owner_id AND p.userid != w.owner_id", ["name AS receivername", "username AS receiverusername"])
                ->joinLeft(["rev" => $this->_dbprefix . "events"], "rev.id=w.owner_id AND w.owner_type = 'event'", ["title","id AS eventid"])
                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id = u.profileImage", "filename AS pimg")
                ->where("p.id=?", $postid)
                ->where("p.userid = ? OR (w.owner_id=? AND w.owner_type = 'profile') OR p.privacy = 0 OR (p.privacy = 1 AND p.userid IN (" . new \Zend_Db_Expr($this->friendslistQuery) . "))", $_SESSION['user']->userid);
        //var_dump($query->__toString());
        return $this->getAdapter()->fetchRow($query);
    }

    /**
     * @param $postid
     * @return array
     */
    public function loadPost($postid) {
        $query = $this->getAdapter()->select()->from(["p" => $this->_dbprefix . "posts"])
                ->join(["u" => $this->_dbprefix . "users"], "u.userid=p.userid", ["name", "username"])
                ->join(["w" => $this->_dbprefix . "walls"], "w.wall_id=p.wall_id")
                ->joinLeft(["img" => $this->_dbprefix . "gallery_images"], "img.id=p.content AND p.type = 'image'", ["filename", "caption"])
                ->joinLeft(["co" => $this->_dbprefix . "comments"], "CASE WHEN p.type = 'post' THEN co.ref_id = p.id ELSE co.ref_id = p.content END AND co.ref_name = p.type", "COUNT(DISTINCT co.id) AS commentcount")
                ->joinLeft(["rus" => $this->_dbprefix . "users"], "rus.userid=w.owner_id AND p.userid != w.owner_id", ["name AS receivername", "username AS receiverusername"])
                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id = u.profileImage", "filename AS pimg")
                ->where("p.userid = ? OR (w.owner_id=? AND w.owner_type = 'profile') OR p.privacy = 0 OR (p.privacy = 1 AND p.userid IN (" . new \Zend_Db_Expr($this->friendslistQuery) . "))", $_SESSION['user']->userid)
                ->where("p.id=?", $postid);        
        $post = $this->getAdapter()->fetchRow($query);        
        if($post['type'] != "image")
            $refid = $post['id'];
        else if($post['type'] == "image")
            $refid = $post['content'];
        $likeTable = new Likes();
        $commentTable = new Comments();
        $likes = $likeTable->getLikes($refid, $post['type']);
        $dislikes = $likeTable->getLikes($refid, $post['type'], 1);
        $comments = $commentTable->get($refid, $post['type'], false, 5);
        return ["post" => $post, "dislikes" => $dislikes, "likes" => $likes, "comments" => $comments];
    }

    /**
     * @param $postid
     * @return bool
     */
    public function deletePost($postid) {
        $res = [];
        $thispost = $this->getPostData($postid);
        if ($thispost['userid'] == $_SESSION['user']->userid || ($thispost['owner_id'] == $_SESSION['user']->userid && $thispost['owner_type'] == "profile")) {
            $likes = new Likes();
            $comments = new Comments();

            $res[] = ($this->delete($this->getAdapter()->quoteInto("id=?", $postid)) !== false);
            $res[] = ($comments->delete($this->getAdapter()->quoteInto("ref_id=? AND ref_name='post'", $postid)) !== False);
            $res[] = ($likes->delete($this->getAdapter()->quoteInto("ref_id=? AND ref_name='post'", $postid)) !== False);
            return !in_array(false, $res);
        }
        return false;
    }

    /**
     * @param $ownerid
     * @param $wallid
     */
    public function deleteByOwner($ownerid, $wallid) {
        $result = $this->fetchAll($this->select()->from($this, "id")->where("userid=?", $ownerid)->orWhere("wall_id=?", $wallid));
        foreach ($result AS $post)
            $this->deletePost($post->id);
    }

}
