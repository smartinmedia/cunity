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
use Cunity\Core\Request\Session;
use Cunity\Notifications\Models\Notifier;

/**
 * Class Guests.
 */
class Guests extends Table
{
    /**
     * @var string
     */
    protected $_name = 'events_guests';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $status
     * @param $userid
     * @param $eventid
     *
     * @return bool
     */
    public function changeStatus($status, $userid, $eventid)
    {
        if ($status < 0) {
            return (0 < $this->delete([$this->getAdapter()->quoteInto('userid=?', $userid), $this->getAdapter()->quoteInto('eventid=?', $eventid)]));
        }
        if ($this->getAdapter()->fetchOne('SELECT COUNT(1) FROM '.$this->_name.' WHERE userid= '.$userid.' AND eventid = '.$eventid)) {
            return (0 < $this->update(['status' => $status], [$this->getAdapter()->quoteInto('userid=?', $userid), $this->getAdapter()->quoteInto('eventid=?', $eventid)]));
        }

        return (false !== $this->insert(['userid' => $userid, 'eventid' => $eventid, 'status' => $status]));
    }

    /**
     * @param $eventid
     * @param $users
     * @param int  $status
     * @param bool $invitation
     *
     * @return bool
     */
    public function addGuests($eventid, $users, $status = 0, $invitation = false)
    {
        if (is_array($users) && !empty($users)) {
            foreach ($users as $user) {
                $this->insert(['userid' => intval($user), 'eventid' => intval($eventid), 'status' => $status]);
                Notifier::notify($user, Session::get('user')->userid, 'eventInvitation', 'index.php?m=events&action='.$eventid);
            }
        } else {
            $this->insert(['userid' => intval($users), 'eventid' => intval($eventid), 'status' => $status]);
            if ($invitation) {
                Notifier::notify($users, Session::get('user')->userid, 'eventInvitation', 'index.php?m=events&action='.$eventid);
            }
        }

        return true;
    }

    /**
     * @param $eventid
     * @param bool $sort
     * @param int  $limit
     *
     * @return array|bool
     */
    public function getGuests($eventid, $sort = true, $limit = 4)
    {
        $guests = [];
        $res = $this->getAdapter()->fetchAll(
            $this->getAdapter()->select()->from(['g' => $this->_name])
                ->joinLeft(['u' => $this->_dbprefix.'users'], 'g.userid=u.userid', ['username', 'name'])
                ->joinLeft(['i' => $this->_dbprefix.'gallery_images'], 'i.id=u.profileImage', 'filename')
                ->where('g.eventid=?', $eventid)
        );
        if ($res !== null) {
            if ($sort) {
                foreach ($res as $guest) {
                    if ($guest['status'] == 0 && (($limit > 0 && count($guests['invited']) < $limit) || $limit == 0)) {
                        $guests['invited'][] = $guest;
                    } elseif ($guest['status'] == 1 && (($limit > 0 && count($guests['maybe']) < $limit) || $limit == 0)) {
                        $guests['maybe'][] = $guest;
                    } elseif ($guest['status'] == 2 && (($limit > 0 && count($guests['attending']) < $limit) || $limit == 0)) {
                        $guests['attending'][] = $guest;
                    }
                }

                return $guests;
            }

            return $res;
        }

        return false;
    }
}
