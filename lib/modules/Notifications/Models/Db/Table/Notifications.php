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

namespace Cunity\Notifications\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\Models\Generator\Url;
use Cunity\Notifications\Models\Notifier;

/**
 * Class Notifications.
 */
class Notifications extends Table
{
    /**
     * @var string
     */
    protected $_name = 'notifications';
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
     * @return bool
     */
    public function insertNotification(array $data)
    {
        $data['unread'] = 1;

        return (1 == $this->insert($data));
    }

    /**
     * @return array
     */
    public function getNotifications()
    {
        $result = [];
        $query = $this->getAdapter()->select()->from(['n' => $this->_name])
            ->joinLeft(['u' => $this->_dbprefix.'users'], 'n.ref_userid=u.userid', ['name', 'username'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id = u.profileImage', ['filename AS pimg', 'albumid AS palbumid'])
            ->where('n.userid=?', $_SESSION['user']->userid)
            ->order('n.unread DESC')
            ->limit(5);
        $res = $this->getAdapter()->fetchAll($query);
        $resCount = count($res);
        for ($i = 0; $i < $resCount; $i++) {
            $d = Notifier::getNotificationData($res[$i]['type']);
            $res[$i]['message'] = \sprintf($d, $res[$i]['name']);
            $res[$i]['target'] = Url::convertUrl($res[$i]['target']);
            if ($res[$i]['unread'] == 1) {
                $result['new']++;
            }
        }
        $result['all'] = $res;

        return $result;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function read($id)
    {
        return ($this->update(['unread' => 0], $this->getAdapter()->quoteInto('id=?', $id)) !== false);
    }
}
