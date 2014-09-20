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
 * Class Boards
 * @package Cunity\Forums\Models\Db\Table
 */
class Boards extends Table
{

    /**
     * @var string
     */
    protected $_name = 'forums_boards';
    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $forumid
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function loadBoards($forumid, $limit = 20, $offset = 0)
    {
        $res = $this->getAdapter()->fetchAll(
            $this
                ->getAdapter()
                ->select()
                ->from(["b" => $this->_name])
                ->joinLeft(["t" => $this->_dbprefix . "forums_threads"], "t.board_id=b.id", [""])
                ->joinLeft(["th" => $this->_dbprefix . "forums_threads"], "th.board_id=b.id", new \Zend_Db_Expr("COUNT(th.id) AS threadcount"))
                ->joinLeft(["p" => $this->_dbprefix . "forums_posts"], "p.thread_id=t.id", ["time"])
                ->order("t.time DESC")
                ->where("b.forum_id=?", $forumid)->limit($limit, $offset)->group("b.id")
        );
        if ($res !== null && $res !== false) {
            return $res;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function loadBoardData($id)
    {
        $res = $this->getAdapter()->fetchRow($this->getAdapter()->select()->from(["b" => $this->_name])
            ->joinLeft(["f" => $this->_dbprefix . "forums"], "f.id=b.forum_id", [new \Zend_Db_Expr("f.title as parenttitle")])->where("b.id=?", $id));
        if ($res == null || $res["id"] == null) {
            return false;
        }
        return $res;
    }

    /**
     * @param array $data
     * @return array|bool
     */
    public function add(array $data)
    {
        $res = $this->insert($data);
        if ($res !== false) {
            return array_merge(["id" => $res], $data);
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteBoardsByForumId($id)
    {
        $result = [];
        $res = $this->fetchAll($this->select()->where("forum_id=?", $id));
        foreach ($res as $r) {
            $result[] = $this->deleteBoard($r->id);
        }
        return !in_array(false, $result);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteBoard($id)
    {
        $threads = new Threads;
        $r = $threads->deleteThreadsByBoardId($id);
        if ($r) {
            return ($this->delete($this->getAdapter()->quoteInto("id=?", $id)) > 0);
        }
        return false;
    }
}
