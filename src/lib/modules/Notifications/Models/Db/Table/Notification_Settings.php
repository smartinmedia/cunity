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

namespace Notifications\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Notification_Settings
 * @package Notifications\Models\Db\Table
 */
class Notification_Settings extends Table {

    /**
     * @var string
     */
    protected $_name = 'notification_settings';

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $name
     * @param $userid
     * @return int|string
     */
    public function getSetting($name, $userid) {
        $res = $this->fetchRow($this->select()->from($this, "value")->where("userid=?", $userid)->where("name=?", $name));
        if ($res == NULL || $res == false)
            return 3;
        return $res->value;
    }

    /**
     * @param $userid
     * @return int|string
     */
    public function getSettings($userid = null)
    {
        if (null === $userid)
        {
            $userid = $_SESSION['user']->userid;
        }

        /** @var $res \Zend_Db_Table_Row */
        $res = $this->fetchAll($this->select()->from($this, ['name', 'value'])->where("userid=?", $userid));

        $returnValue = [];

        foreach ($res->toArray() as $_setting)
        {
            $returnValue[$_setting['name']] = $_setting['value'];
        }

        return $returnValue;
    }

    /**
     * @param array $values
     * @return bool
     */
    public function updateSettings(array $values) {
        $res = [];
        $res[] = (0 < $this->delete($this->getAdapter()->quoteInto("userid=?", $_SESSION['user']->userid)));
        foreach ($values AS $name => $value)
            $res[] = $this->insert(["userid" => $_SESSION['user']->userid, "name" => $name, "value" => $value]);
        return !in_array(false, $res);
    }

}
