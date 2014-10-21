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

namespace Cunity\Profile\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\Models\Db\Row\User;

/**
 * Class ProfileFieldsUsers
 * @package Profile\Models\Db\Table
 */
class ProfileFieldsUsers extends Table
{

    /**
     * @var string
     */
    protected $_name = 'profilefields_users';

    /**
     * @var User
     */
    protected $user = null;

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     * @param array $config
     * @param null $user
     */
    public function __construct($config = array(), $user = null)
    {
        if (null !== $user) {
            $this->user = $user;
        }

        parent::__construct($config);
    }


    /**
     * @param null $userid
     * @return array
     */
    public function getAllByUserId($userid = null)
    {
        if (null === $userid) {
            $userid = $this->user->userid;
        }

        $query = $this->getAdapter()->select()
            ->from(["p" => $this->getTableName()])
            ->where('user_id = ' . $userid);

        $result = $this->getAdapter()->fetchAll($query);

        return $result;
    }

    /**
     * @param array $data
     * @param String $where
     * @return int
     */
    public function update(array $data, $where)
    {
        $results = $this->getAllByUserId();

        foreach ($data as $key => $value) {
//            $query = $this->getAdapter()->select()->from(['p' => $this->getTableName()])->where('profilefield_id = '.$key.' AND user_id = '.$this->user->userid);
//            $result = $this->getAdapter()->fetchOne($query);
//            fb($result);
//            fb($result->toArray());
//            parent::update(['value' => $data['value']], 'id = '.$data['id']);
        }
    }
}
