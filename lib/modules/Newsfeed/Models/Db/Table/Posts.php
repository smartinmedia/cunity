<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * 1. YOU MUST NOT CHANGE THE LICENSE FOR THE SOFTWARE OR ANY PARTS HEREOF! IT MUST REMAIN AGPL.
 * 2. YOU MUST NOT REMOVE THIS COPYRIGHT NOTES FROM ANY PARTS OF THIS SOFTWARE!
 * 3. NOTE THAT THIS SOFTWARE CONTAINS THIRD-PARTY-SOLUTIONS THAT MAY EVENTUALLY NOT FALL UNDER (A)GPL!
 * 4. PLEASE READ THE LICENSE OF THE CUNITY SOFTWARE CAREFULLY!
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program (under the folder LICENSE).
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * If your software can interact with users remotely through a computer network,
 * you have to make sure that it provides a way for users to get its source.
 * For example, if your program is a web application, its interface could display
 * a "Source" link that leads users to an archive of the code. There are many ways
 * you could offer source, and different solutions will be better for different programs;
 * see section 13 of the GNU Affero General Public License for the specific requirements.
 *
 * #####################################################################################
 */

namespace Cunity\Newsfeed\Models\Db\Table;

use Cunity\Comments\Models\Db\Table\Comments;
use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Likes\Models\Db\Table\Likes;
use Cunity\Notifications\Models\Notifier;

/**
 * Class Posts.
 */
class Posts extends Table
{
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
    public function __construct()
    {
        parent::__construct();
        $this->friendslistQuery = new \Zend_Db_Expr(
            $this
                ->getAdapter()
                ->select()
                ->from($this->_dbprefix.'relations', new \Zend_Db_Expr('(CASE WHEN sender = '.$_SESSION['user']->userid.' THEN receiver WHEN receiver = '.$_SESSION['user']->userid.' THEN sender END)'))
                ->where('status > 0')
                ->where('sender=?', $_SESSION['user']->userid)
                ->orWhere('receiver=?', $_SESSION['user']->userid)
        );
    }

    /**
     * @param array $data
     *
     * @return bool|mixed
     */
    public function post(array $data)
    {
        if (isset($data['wall_owner_id']) && isset($data['wall_owner_type']) && !empty($data['wall_owner_id']) && !empty($data['wall_owner_type'])) {
            $walls = new Walls();
            $wallid = $walls->getWallId($data['wall_owner_id'], $data['wall_owner_type']);
            $notification = new Notifier();
            $notification->notify($data['wall_owner_id'], $_SESSION['user']->userid, 'wall_post', 'index.php?m=wall_post&action='.$data['wall_owner_id']);
        } else {
            $wallid = $_POST['wallid'];
        }
        $res = $this->insert(['userid' => $data['userid'], 'wall_id' => $wallid, 'privacy' => $data['privacy'], 'content' => $data['content'], 'time' => new \Zend_Db_Expr('UTC_TIMESTAMP()'), 'type' => $data['type']]);
        if ($res !== null) {
            return $this->getPostData($res);
        } else {
            return false;
        }
    }

    /**
     * @param $postid
     *
     * @return mixed
     */
    private function getPostData($postid)
    {
        $query = $this->getAdapter()->select()->from(['p' => $this->getTableName()])
            ->join(['u' => $this->_dbprefix.'users'], 'u.userid=p.userid', ['name', 'username'])
            ->join(['w' => $this->_dbprefix.'walls'], 'w.wall_id=p.wall_id')
            ->joinLeft(['img' => $this->_dbprefix.'gallery_images'], "img.id=p.content AND p.type = 'image'", ['filename', 'caption', 'id AS refid'])
            ->joinLeft(['rus' => $this->_dbprefix.'users'], 'rus.userid=w.owner_id AND p.userid != w.owner_id', ['name AS receivername', 'username AS receiverusername'])
            ->joinLeft(['rev' => $this->_dbprefix.'events'], "rev.id=w.owner_id AND w.owner_type = 'event'", ['title', 'id AS eventid'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id = u.profileImage', 'filename AS pimg')
            ->where('p.id=?', $postid)
            ->where("p.userid = ? OR (w.owner_id=? AND w.owner_type = 'profile') OR p.privacy = 0 OR (p.privacy = 1 AND p.userid IN (".new \Zend_Db_Expr($this->friendslistQuery).'))', $_SESSION['user']->userid);

        return $this->getAdapter()->fetchRow($query);
    }

    /**
     * @param $postid
     *
     * @return array
     */
    public function loadPost($postid)
    {
        $refid = null;
        $query = $this->getAdapter()->select()->from(['p' => $this->getTableName()])
            ->join(['u' => $this->_dbprefix.'users'], 'u.userid=p.userid', ['name', 'username'])
            ->join(['w' => $this->_dbprefix.'walls'], 'w.wall_id=p.wall_id')
            ->joinLeft(['img' => $this->_dbprefix.'gallery_images'], "img.id=p.content AND p.type = 'image'", ['filename', 'caption'])
            ->joinLeft(['co' => $this->_dbprefix.'comments'], "CASE WHEN p.type = 'post' THEN co.ref_id = p.id ELSE co.ref_id = p.content END AND co.ref_name = p.type", 'COUNT(DISTINCT co.id) AS commentcount')
            ->joinLeft(['rus' => $this->_dbprefix.'users'], 'rus.userid=w.owner_id AND p.userid != w.owner_id', ['name AS receivername', 'username AS receiverusername'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id = u.profileImage', 'filename AS pimg')
            ->where("p.userid = ? OR (w.owner_id=? AND w.owner_type = 'profile') OR p.privacy = 0 OR (p.privacy = 1 AND p.userid IN (".new \Zend_Db_Expr($this->friendslistQuery).'))', $_SESSION['user']->userid)
            ->where('p.id=?', $postid);
        $post = $this->getAdapter()->fetchRow($query);
        if ($post['type'] != 'image') {
            $refid = $post['id'];
        } elseif ($post['type'] == 'image') {
            $refid = $post['content'];
        }
        $likeTable = new Likes();
        $commentTable = new Comments();
        $likes = $likeTable->getLikes($refid, $post['type']);
        $dislikes = $likeTable->getLikes($refid, $post['type'], 1);
        $comments = $commentTable->get($refid, $post['type'], false, 5);

        return ['post' => $post, 'dislikes' => $dislikes, 'likes' => $likes, 'comments' => $comments];
    }

    /**
     * @param $ownerid
     * @param $wallid
     */
    public function deleteByOwner($ownerid, $wallid)
    {
        $result = $this->fetchAll($this->select()->from($this, 'id')->where('userid=?', $ownerid)->orWhere('wall_id=?', $wallid));
        foreach ($result as $post) {
            $this->deletePost($post->id);
        }
    }

    /**
     * @param $postid
     *
     * @return bool
     */
    public function deletePost($postid)
    {
        $res = [];
        $thispost = $this->getPostData($postid);
        if ($thispost['userid'] == $_SESSION['user']->userid || ($thispost['owner_id'] == $_SESSION['user']->userid && $thispost['owner_type'] == 'profile')) {
            $likes = new Likes();
            $comments = new Comments();

            $res[] = ($this->delete($this->getAdapter()->quoteInto('id=?', $postid)) !== false);
            $res[] = ($comments->delete($this->getAdapter()->quoteInto("ref_id=? AND ref_name='post'", $postid)) !== false);
            $res[] = ($likes->delete($this->getAdapter()->quoteInto("ref_id=? AND ref_name='post'", $postid)) !== false);

            return !in_array(false, $res);
        }

        return false;
    }
}
