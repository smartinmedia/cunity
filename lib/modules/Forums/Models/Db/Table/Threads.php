<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################
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

namespace Cunity\Forums\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Threads
 * @package Cunity\Forums\Models\Db\Table
 */
class Threads extends Table {

    /**
     * @var string
     */
    protected $_name = 'forums_threads';
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
     * @param $boardid
     * @return array|bool
     */
    public function loadThreads($boardid) {
        $res = $this->getAdapter()->fetchAll($this->getAdapter()->select()
                        ->from(["t" => $this->_dbprefix . "forums_threads"])
                        ->joinLeft(["p" => $this->_dbprefix . "forums_posts"], "p.thread_id=t.id", ["time"])
                        ->joinLeft(["pc" => $this->_dbprefix . "forums_posts"], "pc.thread_id=t.id", new \Zend_Db_Expr("COUNT(DISTINCT pc.id) AS postcount"))
                        ->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid=p.userid", ["name", "username"])
                        ->joinLeft(["c" => $this->_dbprefix . "forums_categories"], "c.id=t.category", ["name AS categoryName", "tag AS categoryTag"])
                        ->order("t.important DESC")
                        ->order("p.time DESC")
                        ->where("pc.id IS NOT NULL")
                        ->where("t.board_id=?", $boardid)
                        ->group("t.id"));
        if ($res !== NULL && $res !== false)
            return $res;
        return false;
    }

    /**
     * @param $category
     * @return array|bool
     */
    public function loadCategoryThreads($category) {
        $res = $this->getAdapter()->fetchAll($this->getAdapter()->select()
                        ->from(["t" => $this->_dbprefix . "forums_threads"])
                        ->joinLeft(["p" => $this->_dbprefix . "forums_posts"], "p.thread_id=t.id", ["time"])
                        ->joinLeft(["pc" => $this->_dbprefix . "forums_posts"], "pc.thread_id=t.id", new \Zend_Db_Expr("COUNT(DISTINCT pc.id) AS postcount"))
                        ->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid=p.userid", ["name", "username"])
                        ->joinLeft(["c" => $this->_dbprefix . "forums_categories"], "c.id=t.category", ["name AS categoryName", "tag AS categoryTag"])
                        ->order("t.important DESC")
                        ->order("p.time DESC")
                        ->where("t.category=?", $category)
                        ->group("t.id"));
        if ($res !== NULL && $res !== false)
            return $res;
        return false;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function loadThreadData($id) {
        $res = $this->getAdapter()->fetchRow($this->getAdapter()->select()->from(["t" => $this->_name])
                        ->joinLeft(["b" => $this->_dbprefix . "forums_boards"], "b.id=t.board_id", ["forum_id", new \Zend_Db_Expr("b.title as boardtitle")])
                        ->joinLeft(["pc" => $this->_dbprefix . "forums_posts"], "pc.thread_id=t.id", new \Zend_Db_Expr("COUNT(DISTINCT pc.id) AS postcount"))
                        ->joinLeft(["f" => $this->_dbprefix . "forums"], "f.id=b.forum_id", [new \Zend_Db_Expr("f.title as forumtitle")])
                        ->where("t.id=?", $id));
        if ($res == NULL || $res["id"] == NULL)
            return false;
        return $res;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteThread($id) {
        $posts = new Posts;
        $r = $posts->delete($posts->getAdapter()->quoteInto("thread_id=?", $id));
        if ($r !== false)
            return ($this->delete($this->getAdapter()->quoteInto("id=?", $id)) > 0);
        else
            return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteThreadsByBoardId($id) {
        $result = [];
        $res = $this->fetchAll($this->select()->where("board_id=?", $id));
        foreach ($res AS $r)
            $result[] = $this->deleteThread($r->id);
        return !in_array(false, $result);
    }

}
