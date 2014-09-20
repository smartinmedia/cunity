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

namespace Cunity\Likes\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Likes
 * @package Cunity\Likes\Models\Db\Table
 */
class Likes extends Table {

    /**
     * @var string
     */
    protected $_name = 'likes';
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
     * @param $referenceId
     * @param $referenceName
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getLike($referenceId, $referenceName) {
        return $this->fetchRow($this->select()->from($this, ["id", "dislike"])->where("ref_id=?", $referenceId)->where("ref_name=?", $referenceName)->where("userid=?", $_SESSION['user']->userid));
    }

    /**
     * @param $referenceId
     * @param $referenceName
     * @return array
     */
    public function countLikes($referenceId, $referenceName) {
        $likes = $this->fetchRow($this->select()->from($this, new \Zend_Db_Expr("COUNT(*) AS c"))->where("ref_name=?", $referenceName)->where("ref_id=?", $referenceId)->where("dislike=0"));
        $dislikes = $this->fetchRow($this->select()->from($this, new \Zend_Db_Expr("COUNT(*) AS c"))->where("ref_name=?", $referenceName)->where("ref_id=?", $referenceId)->where("dislike=1"));
        return ["dislikes" => $dislikes['c'], "likes" => $likes['c']];
    }

    /**
     * @param $referenceId
     * @param $referenceName
     * @param int $dislike
     * @return array
     */
    public function getLikes($referenceId, $referenceName, $dislike = 0) {
        return $this->getAdapter()->fetchAll($this->getAdapter()->select()->from(["l" => $this->_dbprefix . "likes"])->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid=l.userid", ["username", "name"])->joinLeft(["i" => $this->_dbprefix . "gallery_images"], "i.id=u.profileImage", "filename")->where("ref_name=?", $referenceName)->where("ref_id=?", $referenceId)->where("dislike=?", $dislike));
    }

    /**
     * @param $referenceId
     * @param $referenceName
     * @return array|bool
     */
    public function like($referenceId, $referenceName) {
        $res = $this->getLike($referenceId, $referenceName);
        if ($res != NULL && $res->dislike == 1) {
            $res->dislike = 0;
            if ($res->save())
                return $this->countLikes($referenceId, $referenceName);
        }else if ($this->insert(["ref_id" => $referenceId, "ref_name" => $referenceName, "userid" => $_SESSION['user']->userid]) !== NULL)
            return $this->countLikes($referenceId, $referenceName);
        return false;
    }

    /**
     * @param $referenceId
     * @param $referenceName
     * @return array|bool
     */
    public function dislike($referenceId, $referenceName) {
        $res = $this->getLike($referenceId, $referenceName);
        if ($res != NULL && $res->dislike == 0) {
            $res->dislike = 1;
            if ($res->save())
                return $this->countLikes($referenceId, $referenceName);
        }else if ($this->insert(["ref_id" => $referenceId, "ref_name" => $referenceName, "dislike" => 1, "userid" => $_SESSION['user']->userid]) !== NULL)
            return $this->countLikes($referenceId, $referenceName);
        return false;
    }

    /**
     * @param $referenceId
     * @param $referenceName
     * @return array|bool
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function unlike($referenceId, $referenceName) {
        $res = $this->getLike($referenceId, $referenceName);
        if ($res->delete())
            return $this->countLikes($referenceId, $referenceName);
        return false;
    }

}
