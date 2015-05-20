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

namespace Cunity\Forums\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Forums.
 */
class Forums extends Table
{
    /**
     * @var string
     */
    protected $_name = 'forums';
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
     * @return array
     */
    public function loadForums()
    {
        $query = $this->getAdapter()->select()->from(['f' => $this->getTableName()])
            ->joinLeft(['b' => $this->_dbprefix.'forums_boards'], 'b.forum_id=f.id', new \Zend_Db_Expr('COUNT(b.id) AS boardcount'))
            ->where('f.owner_id IS NULL')
            ->where('f.owner_type IS NULL')
            ->group('f.id');

        return $this->getAdapter()->fetchAll($query);
    }

    /**
     * @param $id
     *
     * @return bool|null|\Zend_Db_Table_Row_Abstract
     *
     * @throws \Zend_Db_Table_Exception
     */
    public function loadForumData($id)
    {
        if (is_array($id) && isset($id['owner_id']) && isset($id['owner_type'])) {
            $res = $this->fetchRow($this->select()->where('owner_id=?', $id['owner_id'])->where('owner_type=?', $id['owner_type']));
        } else {
            $res = $this->find($id)->current();
        }
        if (($res !== null)) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    public function add(array $data)
    {
        $res = $this->insert($data);
        if ($res !== false) {
            return array_merge(['id' => $res], $data);
        }

        return false;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function deleteForum($id)
    {
        $boards = new Boards();
        if ($boards->deleteBoardsByForumId($id)) {
            return ($this->delete($this->getAdapter()->quoteInto('id=?', $id)) > 0);
        }

        return false;
    }
}
