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

namespace Comments\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Comments
 * @package Comments\Models\Db\Table
 */
class Comments extends Table
{

    /**
     * @var string
     */
    protected $_name = 'comments';
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
     * @param $referenceId
     * @param $referenceName
     * @param $content
     * @return array|mixed
     */
    public function addComment($referenceId, $referenceName, $content)
    {
        $res = $this->insert(["ref_id" => $referenceId, "ref_name" => $referenceName, "userid" => $_SESSION['user']->userid, "content" => $content]);
        if ($res !== NULL)
            return $this->getComment($res);
        else
            return ["status" => false];
    }

    /**
     * @param $commentid
     * @return int
     */
    public function removeComment($commentid)
    {
        return $this->delete($this->getAdapter()->quoteInto("id = ?", $commentid));
    }

    /**
     * @param $referenceId
     * @param $referenceName
     * @return int
     */
    public function removeAllComments($referenceId, $referenceName)
    {
        return $this->delete($this->getAdapter()->quoteInto("ref_id = ? AND ref_name = ?", [intval($referenceId), $referenceName]));
    }

    /**
     * @param $commentid
     * @return mixed
     */
    public function getComment($commentid)
    {
        return $this->getAdapter()->fetchRow($this->getAdapter()->select()->from(["c" => $this->_dbprefix . "comments"], ["id", "content", "time", "userid"])->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid = c.userid", ["username", "name"])->joinLeft(["i" => $this->_dbprefix . "gallery_images"], "u.profileImage = i.id", ["filename"])->where("c.id = ?", $commentid));
    }

    /**
     * @param $referenceId
     * @param $referenceName
     * @param bool $last
     * @param int $limit
     * @return array
     */
    public function get($referenceId, $referenceName, $last = false, $limit = 20)
    {
        $query = $this->getAdapter()->select()->from(["c" => $this->_dbprefix . "comments"])->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid = c.userid", ["username", "name"])->joinLeft(["i" => $this->_dbprefix . "gallery_images"], "u.profileImage = i.id", ["filename"])->where("c.ref_id = ?", $referenceId)->where("c.ref_name = ?", $referenceName)->order("c.time DESC")->limit($limit);
        if ($last)
            $query->where("c.id < ?", $last);
        return $this->getAdapter()->fetchAll($query);
    }

}
