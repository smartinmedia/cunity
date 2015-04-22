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
namespace Cunity\Events\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Friends\Models\Generator\FriendQuery;

/**
 * Class Events.
 */
class Events extends Table
{
    /**
     * @var string
     */
    protected $_name = 'events';
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
     * @param array $data
     *
     * @return mixed
     */
    public function addEvent(array $data)
    {
        return $this->insert($data);
    }

    /**
     * @param $eventid
     *
     * @return bool
     */
    public function deleteEvent($eventid)
    {
        return (0 < $this->delete($this->getAdapter()->quoteInto('id=?', $eventid)));
    }

    /**
     * @param $eventid
     *
     * @return mixed
     */
    public function getEventData($eventid)
    {
        $returnValue = null;
        $res = $this->getAdapter()->fetchRow(
            $this
                ->getAdapter()
                ->select()
                ->from(['e' => $this->getTableName()], ['*'])
                ->joinLeft(['g' => $this->_dbprefix.'events_guests'], 'g.eventid=e.id AND g.userid='.$this->getAdapter()->quote($_SESSION['user']->userid), ['guestid', 'status'])
                ->joinLeft(['u' => $this->_dbprefix.'users'], 'e.userid = u.userid', ['username', 'name'])
                ->joinLeft(['i' => $this->_dbprefix.'gallery_images'], 'i.id=e.imageId', ['filename'])
                ->joinLeft(['gc' => $this->_dbprefix.'events_guests'], 'g.eventid=e.id', new \Zend_Db_Expr('COUNT(gc.guestid) AS guestcount'))
                ->group('gc.guestid')
                ->where('e.id=?', $eventid)
        );
        if ($res !== null) {
            $returnValue = $res;
        }

        return $returnValue;
    }

    /**
     * @param $start
     * @param $end
     *
     * @return array
     */
    public function fetchBetween($start, $end)
    {
        $query = $this->getAdapter()->select()->from(['e' => $this->getTableName()], ['*', new \Zend_Db_Expr('UNIX_TIMESTAMP(start)*1000 AS start'), new \Zend_Db_Expr('UNIX_TIMESTAMP(start)*1000 AS end')])
            ->joinLeft(['g' => $this->_dbprefix.'events_guests'], 'g.eventid=e.id AND g.userid='.$this->getAdapter()->quote($_SESSION['user']->userid), ['guestid', 'status'])
            ->joinLeft(['u' => $this->_dbprefix.'users'], 'e.userid = u.userid', ['username', 'name'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], "pi.id = u.profileImage AND e.type = 'birthday'", 'filename AS pimg')
            ->where('e.start BETWEEN '.$this->getAdapter()->quote($start).' AND '.$this->getAdapter()->quote($end))
            ->where("e.type = 'event' OR (e.type = 'birthday' AND e.userid IN (".FriendQuery::getFriendListQuery('=2').'))')
            ->where("(g.guestid IS NULL AND e.type = 'birthday') OR (g.guestid IS NOT NULL AND e.type = 'event')");

        return $this->getAdapter()->fetchAll($query);
    }

    /**
     * @param $eventid
     * @param $values
     *
     * @return bool
     */
    public function updateEvent($eventid, $values)
    {
        return (0 < $this->update($values, $this->getAdapter()->quoteInto('id=?', $eventid)));
    }
}
