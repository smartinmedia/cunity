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

namespace Cunity\Gallery\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\View\View;
use Zend_Db_Table_Row_Abstract;

/**
 * Class Gallery_Albums
 * @package Cunity\Gallery\Models\Db\Table
 */
class Gallery_Albums extends Table {

    /**
     * @var string
     */
    protected $_name = 'gallery_albums';
    /**
     * @var string
     */
    protected $_primary = 'id';
    /**
     * @var string
     */
    protected $_rowClass = "\Cunity\Gallery\Models\Db\Row\Album";

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $albumid
     * @return bool
     */
    public function exists($albumid) {
        $res = $this->fetchRow($this->select()->from($this, "COUNT(albumid) AS count")->where("albumid=?", $albumid));
        return ($res->count > 0);
    }

    /**
     * @param $userid
     * @return mixed
     */
    public function newProfileAlbums($userid) {
        return $this->insert(["owner_id" => $userid, "type" => "profile"]);
    }

    /**
     * @param $userid
     * @return mixed
     */
    public function newNewsfeedAlbums($userid) {
        return $this->insert(["owner_id" => $userid, "type" => "newsfeed"]);
    }

    /**
     * @param $field
     * @param $value
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function search($field, $value) {
        return $this->fetchRow($this->select()->where($this->getAdapter()->quoteIdentifier($field) . " = ?", $value));
    }

    /**
     * @param $albumid
     * @return array|bool
     */
    public function getAlbumData($albumid) {
        $result = $this->fetchRow($this->select()->setIntegrityCheck(false)->from(["a" => $this->_dbprefix . "gallery_albums"])
                        ->joinLeft(["u" => $this->_dbprefix . "users"], "a.owner_type IS NULL AND a.owner_id=u.userid", ["name", "username"])
                        ->joinLeft(["e" => $this->_dbprefix . "events"], "a.owner_type = 'event' AND a.owner_id=e.id", ["title AS eventTitle"])
                        ->joinLeft(["i" => $this->_dbprefix . "gallery_images"], "i.id=u.profileImage AND a.owner_type IS NULL", "filename")
                        ->joinLeft(["ie" => $this->_dbprefix . "gallery_images"], "ie.id=e.imageId AND a.owner_type = 'event'", "filename")
                        ->where("a.id=?", $albumid));
        if ($result instanceof Zend_Db_Table_Row_Abstract) {
            if ($result->type == 'profile')
                $result->title = View::translate("Profile Images");
            else if ($result->type == "newsfeed")
                $result->title = View::translate("Posted Images");
            return $result->toArray();
        }
        return false;
    }

    /**
     * @param $userid
     * @return array
     * @throws \Zend_Db_Table_Exception
     */
    public function loadAlbums($userid) {
        if ($userid == 0) {
            return $this->getAdapter()->fetchAll(
                            $this->getAdapter()->select()
                                    ->from(["a" => $this->info("name")])
                                    ->joinLeft(["i" => $this->_dbprefix . "gallery_images"], "a.cover=i.id", "filename")
                                    ->joinLeft(["u" => $this->_dbprefix . "users"], "a.owner_id=u.userid AND a.owner_type IS NULL", ["u.name", "u.username"])
                                    ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id=u.profileImage", "pi.filename as pimg")
                                    ->where("(a.photo_count > 0) AND ((a.type IS NULL OR a.type = 'shared') AND (a.privacy = 2 OR (a.privacy = 1 AND a.owner_id IN (" . new \Zend_Db_Expr($this->getAdapter()->select()->from($this->_dbprefix . "relations", new \Zend_Db_Expr("(CASE WHEN sender = " . $_SESSION['user']->userid . " THEN receiver WHEN receiver = " . $_SESSION['user']->userid . " THEN sender END)"))->where("status > 0")->where("sender=?", $_SESSION['user']->userid)->orWhere("receiver=?", $_SESSION['user']->userid)) . "))) OR (a.owner_type IS NULL AND a.owner_id=?))", $_SESSION['user']->userid)
                                    ->order("i.time DESC")
            );
        } else {
            return $this->getAdapter()->fetchAll(
                            $this->getAdapter()->select()
                                    ->from(["a" => $this->info("name")])
                                    ->joinLeft(["i" => $this->_dbprefix . "gallery_images"], "a.cover=i.id", "filename")
                                    ->joinLeft(["u" => $this->_dbprefix . "users"], "a.owner_id=u.userid AND a.owner_type IS NULL", ["u.name", "u.username"])
                                    ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id=u.profileImage", "pi.filename as pimg")
                                    ->where("a.photo_count > 0")
                                    ->where("(a.privacy = 2 OR (a.privacy = 1 AND a.owner_type IS NULL AND a.owner_id IN (" . new \Zend_Db_Expr($this->getAdapter()->select()->from($this->_dbprefix . "relations", new \Zend_Db_Expr("(CASE WHEN sender = " . $_SESSION['user']->userid . " THEN receiver WHEN receiver = " . $_SESSION['user']->userid . " THEN sender END)"))->where("status > 0")->where("sender=?", $_SESSION['user']->userid)->orWhere("receiver=?", $_SESSION['user']->userid)) . ")) OR (a.owner_type IS NULL AND a.owner_id=?))", $_SESSION['user']->userid)
                                    ->where("a.owner_id=? AND a.owner_type IS NULL", $userid)
                                    ->order("i.time DESC")
            );
        }
    }

    /**
     * @param $userid
     */
    public function deleteAlbumsByUser($userid) {
        $albums = $this->fetchAll($this->select()->where("userid=?", $userid));
        foreach ($albums AS $album)
            $album->deleteAlbum();
    }

}
