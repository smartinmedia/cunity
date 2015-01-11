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
 * Class ProfileFields
 * @package Profile\Models\Db\Table
 */
class ProfileFields extends Table
{

    /**
     * @var string
     */
    protected $_name = 'profilefields';

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     * @var array
     */
    public static $types = [1 => 'select', 2 => 'radio', 3 => 'text', 4 => 'text', 5 => 'email', 6 => 'date'];

    /**
     * @return array
     */
    public function getAll()
    {
        $query = $this->getAdapter()->select()
            ->from(["pf" => $this->getTableName()])
            ->order("pf.sorting");

        $result = $this->getAdapter()->fetchAll($query);

        foreach ($result as $_key => $_result) {
            $values = [];

            switch (self::$types[$_result['type_id']]) {
                case 'select':
                    $queryValues = $this->getAdapter()->select()
                        ->from(["pfv" => $this->_dbprefix . "profilefields_values"])
                        ->where('profilefield_id = ' . $_result['id'])
                        ->order("pfv.sorting");

                    $values = $queryValues->getAdapter()->fetchAll($queryValues);
                    break;
                default:
                    break;
            }

            $value = new ProfileFieldsUsers([], $_SESSION['user']);
            $res = $value->getByProfileFieldIdAndUserId($_result['id']);
            $result[$_key]['label'] = $_result['value'];
            $result[$_key]['value'] = $res['value'];
            $result[$_key]['values'] = $values;
            $result[$_key]['type'] = self::$types[$_result['type_id']];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getRegistrationFields()
    {
        $query = $this->getAdapter()->select()
            ->from(["pf" => $this->getTableName()])
            ->where('registration = 1')
            ->order("pf.sorting");

        $result = $this->getAdapter()->fetchAll($query);

        foreach ($result as $_key => $_result) {
            $values = [];

            if (self::$types[$_result['type_id']] === 'select' ||
                self::$types[$_result['type_id']] === 'radio') {
                $queryValues = $this->getAdapter()->select()
                    ->from(["pfv" => $this->_dbprefix . "profilefields_values"])
                    ->where('profilefield_id = ' . $_result['id'])
                    ->order("pfv.sorting");

                $values = $queryValues->getAdapter()->fetchAll($queryValues);
            }

            $result[$_key]['values'] = $values;
            $result[$_key]['type'] = self::$types[$_result['type_id']];
        }

        return $result;
    }
}
