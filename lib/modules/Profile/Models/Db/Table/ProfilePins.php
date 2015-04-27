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

namespace Cunity\Profile\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class ProfilePins.
 */
class ProfilePins extends Table
{
    /**
     * @var string
     */
    protected $_name = 'profile_pins';
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
     *
     * @return array|\Zend_Db_Table_Rowset_Abstract
     */
    public function getAllByUser($userid)
    {
        $pt = new Privacy();
        $res = $pt->checkPrivacy('visit', $userid);
        if ($res) {
            return $this->fetchAll($this->select()->where('userid=?', $userid)->order('row'));
        } else {
            return ['status' => true];
        }
    }

    /**
     * @param $columns
     * @param $row
     * @param $pinid
     *
     * @return int
     */
    public function updatePosition($columns, $row, $pinid)
    {
        return $this->update(['column' => $columns, 'row' => $row], $this->getAdapter()->quoteInto('id=?', $pinid));
    }
}
