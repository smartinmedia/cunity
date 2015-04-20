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

namespace Cunity\Messages\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Messages.
 */
class Messages extends Table
{
    /**
     * @var string
     */
    protected $_name = 'messages';
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
     * @param $userid
     * @param $cid
     *
     * @return bool
     */
    public function deleteByUser($userid, $cid)
    {
        return (0 < $this->delete([$this->getAdapter()->quoteInto('sender=?', $userid), $this->getAdapter()->quoteInto('conversation=?', $cid)]));
    }

    /**
     * @param $conversation_id
     * @param int $offset
     * @param int $refresh
     *
     * @return array
     */
    public function loadByConversation($conversation_id, $offset = 0, $refresh = 0)
    {
        $query = $this->getAdapter()->select()
            ->from($this->_dbprefix.'messages AS m')
            ->where('conversation = ?', $conversation_id)
            ->join($this->_dbprefix.'users AS us', 'm.sender = us.userid', ['us.username', 'us.name'])
            ->joinLeft($this->_dbprefix.'gallery_images AS img', 'img.id = us.profileImage', ['filename AS pimg'])
            ->order('time DESC');

        if ($refresh > 0) {
            $query->where('m.id > ?', $refresh);
        } else {
            $query->limit(20, $offset);
        }

        return $this->getAdapter()->fetchAll($query);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function insert(array $data)
    {
        $conversation = new Conversations();
        $conversation->markAsUnRead($data['conversation']);

        return parent::insert($data);
    }
}
