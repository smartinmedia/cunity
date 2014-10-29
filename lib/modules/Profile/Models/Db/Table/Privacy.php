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

/**
 * Class Privacy
 * @package Cunity\Profile\Models\Db\Table
 *
 * @property \Zend_Db_Table_Row_Abstract value
 */
class Privacy extends Table
{

    /**
     * @var array
     */
    private static $privacies = ["message" => 3, "visit" => 3, "posts" => 3, "search" => 3];
    /**
     * @var string
     */
    protected $_name = 'privacy';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $type
     * @param $userid
     * @return bool
     */
    public function checkPrivacy($type, $userid)
    {
        if ($userid == $_SESSION['user']->userid) {
            return true;
        }
        $pri = $this->getPrivacy($type, $userid);
        if ($pri == 3) {
            return true;
        } /** @noinspection PhpUndefinedMethodInspection */ elseif ($pri == 1 && $_SESSION['user']->isFriend($userid)) {
            return true;
        }

        return false;
    }

    /**
     * @param bool $type
     * @param int $userid
     * @return array
     */
    public function getPrivacy($type = false, $userid = 0)
    {
        if ($userid == 0) {
            $userid = $_SESSION['user']->userid;
        }
        if ($type === false) {
            $res = $this->fetchAll($this->select()->where("userid=?", $userid));
            $result = [];
            foreach ($res as $p) {
                $result[$p->type] = $p->value;
            }
            return $result;
        } else {
            $res = $this->fetchAll($this->select()->where("userid=?", $userid)->where("type=?", $type));
            if ($res === null || $res === false) {
                return self::$privacies[$type];
            }
            return $res->value;
        }
    }

    /**
     * @param $userid
     * @param $privacyName
     * @param int $val
     * @return mixed
     */
    public function updatePrivacy($userid, $privacyName, $val = 0)
    {
        if (is_array($privacyName)) {
            $res = [];
            foreach ($privacyName as $type => $val) {
                $res[] = $this->updatePrivacy($userid, $type, $val);
            }
            return !in_array(false, $res);
        }
        $res = $this->fetchRow($this->select()->where("userid=?", $userid)->where("type=?", $privacyName));
        if ($res !== null) {
            $res->value = $val;
            return $res->save();
        } else {
            return $this->insert(["userid" => $userid, "type" => $privacyName, "value" => $val]);
        }
    }
}
