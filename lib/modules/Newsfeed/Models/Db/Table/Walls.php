<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2015 Smart In Media GmbH & Co. KG                            ##
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

use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\Request\Session;
use Cunity\Events\Models\Generator\EventsQuery;
use Cunity\Friends\Models\Generator\FriendQuery;

/**
 * Class Walls.
 */
class Walls extends Table
{
    /**
     * @var string
     */
    protected $_name = 'walls';
    /**
     * @var string
     */
    protected $_primary = 'wall_id';
    /**
     * @var string|\Zend_Db_Expr
     */
    private $friendslistQuery = '';
    /**
     * @var string|\Zend_Db_Expr
     */
    private $eventslistQuery = '';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->friendslistQuery = FriendQuery::getFriendListQuery();
        $this->eventslistQuery = EventsQuery::getEventsListQuery();
    }

    /**
     * @param $offset
     * @param int   $refresh
     * @param array $filter
     *
     * @return array
     */
    public function getNewsfeed($offset, $refresh = 0, $filter = [])
    {
        $subquery = new \Zend_Db_Expr($this->select()->from($this, 'wall_id')->where('(owner_id IN ('.$this->friendslistQuery.") OR owner_id = ?) AND owner_type = 'profile'", Session::get('user')->userid)
            ->orWhere("owner_type = 'event' AND owner_id IN (".$this->eventslistQuery.')'));
        $query = $this->getAdapter()->select()->from(['p' => $this->_dbprefix.'posts'])
            ->join(['w' => $this->getTableName()], 'w.wall_id=p.wall_id')
            ->join(['u' => $this->_dbprefix.'users'], 'u.userid=p.userid', ['name', 'username'])
            ->joinLeft(['img' => $this->_dbprefix.'gallery_images'], "img.id=p.content AND p.type = 'image'", ['filename', 'caption', 'id AS refid'])
            ->joinLeft(['rus' => $this->_dbprefix.'users'], "rus.userid=w.owner_id AND p.userid != w.owner_id AND w.owner_type = 'profile'", ['name AS receivername', 'username AS receiverusername'])
            ->joinLeft(['rev' => $this->_dbprefix.'events'], "rev.id=w.owner_id AND w.owner_type = 'event'", ['title', 'id AS eventid'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id = u.profileImage', 'filename AS pimg')
            ->joinLeft(['co' => $this->_dbprefix.'comments'], "CASE WHEN p.type != 'image' THEN co.ref_id = p.id ELSE co.ref_id = p.content END AND co.ref_name = p.type", 'COUNT(DISTINCT co.id) AS commentcount')
            ->joinLeft(['li' => $this->_dbprefix.'likes'], "CASE WHEN p.type != 'image' THEN li.ref_id = p.id ELSE li.ref_id = p.content END AND li.ref_name = p.type AND li.dislike = 0", 'COUNT(DISTINCT li.id) AS likecount')
            ->joinLeft(['di' => $this->_dbprefix.'likes'], "CASE WHEN p.type != 'image' THEN di.ref_id = p.id ELSE di.ref_id = p.content END AND di.ref_name = p.type AND di.dislike = 1", 'COUNT(DISTINCT di.id) AS dislikecount')
            ->joinLeft(['ld' => $this->_dbprefix.'likes'], "CASE WHEN p.type != 'image' THEN ld.ref_id = p.id ELSE ld.ref_id = p.content END AND ld.ref_name = p.type AND ld.userid = ".$this->getAdapter()->quote(Session::get('user')->userid), 'ld.dislike AS liked')
            ->where('p.wall_id IN ('.$subquery.') OR p.wall_id IN ('.new \Zend_Db_Expr($this->getAdapter()->select()->from($this->_dbprefix.'walls', 'wall_id')->where('owner_id = ?', Session::get('user')->userid)->where("owner_type = 'profile'")).')')
            ->where("(w.owner_id=? AND w.owner_type = 'profile') OR p.privacy = 0 OR (p.privacy = 1 AND p.userid IN (".new \Zend_Db_Expr($this->friendslistQuery).'))', Session::get('user')->userid)
            ->group(new \Zend_Db_Expr('p.id'))
            ->order('p.id DESC');
        if (!empty($filter)) {
            $query->where('p.type IN (?)', $filter);
        }
        if ($refresh > 0) {
            $query->where('p.id > ?', $refresh);
        } else {
            $query->limit(20, $offset);
        }

        $res = $this->getAdapter()->fetchAll($query);
        foreach ($res as &$result) {
            if ($result['type'] == 'video') {
                $result['content'] = json_decode($result['content']);
            }
        }

        return $res;
    }

    /**
     * @param $ownerid
     * @param $ownertype
     * @param $offset
     * @param int   $refresh
     * @param array $filter
     *
     * @return array
     */
    public function getWall($ownerid, $ownertype, $offset, $refresh = 0, $filter = [])
    {
        $query = $this->getAdapter()->select()->from(['p' => $this->_dbprefix.'posts'])
            ->join(['w' => $this->getTableName()], 'w.wall_id=p.wall_id')
            ->join(['u' => $this->_dbprefix.'users'], 'u.userid=p.userid', ['name', 'username'])
            ->joinLeft(['img' => $this->_dbprefix.'gallery_images'], "img.id=p.content AND p.type = 'image'", ['filename', 'caption', 'id AS refid'])
            ->joinLeft(['rus' => $this->_dbprefix.'users'], "w.owner_type = 'profile' AND rus.userid=w.owner_id AND p.userid != w.owner_id AND w.owner_id != ".$ownerid, ['name AS receivername', 'username AS receiverusername'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id = u.profileImage', 'filename AS pimg')
            ->joinLeft(['co' => $this->_dbprefix.'comments'], "CASE WHEN p.type != 'image' THEN co.ref_id = p.id ELSE co.ref_id = p.content END AND co.ref_name = p.type", 'COUNT(DISTINCT co.id) AS commentcount')
            ->joinLeft(['li' => $this->_dbprefix.'likes'], "CASE WHEN p.type != 'image' THEN li.ref_id = p.id ELSE li.ref_id = p.content END AND li.ref_name = p.type AND li.dislike = 0", 'COUNT(DISTINCT li.id) AS likecount')
            ->joinLeft(['di' => $this->_dbprefix.'likes'], "CASE WHEN p.type != 'image' THEN di.ref_id = p.id ELSE di.ref_id = p.content END AND di.ref_name = p.type AND di.dislike = 1", 'COUNT(DISTINCT di.id) AS dislikecount')
            ->joinLeft(['ld' => $this->_dbprefix.'likes'], "CASE WHEN p.type != 'image' THEN ld.ref_id = p.id ELSE ld.ref_id = p.content END AND ld.ref_name = p.type AND ld.userid = ".$this->getAdapter()->quote(Session::get('user')->userid), 'ld.dislike AS liked')
            ->where('(p.wall_id = ('.new \Zend_Db_Expr($this->select()->from($this, 'wall_id')->where('owner_id = ?', intval($ownerid))->where('owner_type = ?', $ownertype)).')) OR p.userid = ? AND '.$this->getAdapter()->quote($ownertype)." = 'profile'", $ownerid)
            ->where("(p.userid = ?) OR (w.owner_id = ? AND w.owner_type = 'profile') OR p.privacy = 0 OR (p.privacy = 1 AND p.userid IN (".new \Zend_Db_Expr($this->friendslistQuery).'))', intval(Session::get('user')->userid))
            ->group('p.id')
            ->order('p.id DESC');
        if (!empty($filter)) {
            $query->where('p.type IN (?)', $filter);
        }
        if ($refresh > 0) {
            $query->where('p.id > ?', $refresh);
        } else {
            $query->limit(20, $offset);
        }

        $res = $this->getAdapter()->fetchAll($query);
        foreach ($res as &$result) {
            if ($result['type'] == 'video') {
                $result['content'] = json_decode($result['content']);
            }
        }

        return $res;
    }

    /**
     * @param $userid
     * @param string $type
     *
     * @return bool
     */
    public function createWall($userid, $type = 'profile')
    {
        return (0 < $this->insert(['owner_id' => $userid, 'owner_type' => $type]));
    }

    /**
     * @param $ownerid
     * @param $ownertype
     */
    public function deleteWallByOwner($ownerid, $ownertype)
    {
        $wallid = $this->getWallId($ownerid, $ownertype);
        $this->delete($this->getAdapter()->quoteInto('wall_id = ?', $wallid));
        $posts = new Posts();
        $posts->deletebyOwner($ownerid, $wallid);
    }

    /**
     * @param $ownerid
     * @param $ownertype
     *
     * @return string
     */
    public function getWallId($ownerid, $ownertype)
    {
        $res = $this->fetchRow($this->select()->from($this, 'wall_id')->where('owner_id=?', $ownerid)->where('owner_type=?', $ownertype)->limit(1));

        return $res['wall_id'];
    }
}
