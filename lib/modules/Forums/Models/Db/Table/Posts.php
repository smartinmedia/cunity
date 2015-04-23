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
namespace Cunity\Forums\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Posts.
 */
class Posts extends Table
{
    /**
     * @var string
     */
    protected $_name = 'forums_posts';
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
     * @param $thread_id
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function loadPosts($thread_id, $limit = 20, $offset = 0)
    {
        $query = $this->getAdapter()->select()->from(['p' => $this->_name])
            ->joinLeft(['u' => $this->_dbprefix.'users'], 'u.userid=.p.userid', ['name', 'username'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id=u.profileImage', ['filename'])
            ->joinLeft(['pc' => $this->getTableName()], 'pc.thread_id=p.thread_id', new \Zend_Db_Expr('COUNT(DISTINCT pc.id) AS postcount'))
            ->where('p.thread_id=?', $thread_id)
            ->group('p.id')
            ->order('time')
            ->limit($limit, $offset);

        return $this->getAdapter()->fetchAll($query);
    }

    /**
     * @param array $data
     *
     * @return bool|mixed
     */
    public function post(array $data)
    {
        $res = $this->insert(array_merge($data, ['userid' => $_SESSION['user']->userid]));
        if ($res !== false) {
            return $this->getPost($res);
        }

        return false;
    }

    /**
     * @param $postid
     *
     * @return mixed
     */
    public function getPost($postid)
    {
        $query = $this->getAdapter()->select()->from(['p' => $this->_name])
            ->joinLeft(['u' => $this->_dbprefix.'users'], 'u.userid=.p.userid', ['name', 'username'])
            ->joinLeft(['pi' => $this->_dbprefix.'gallery_images'], 'pi.id=u.profileImage', ['filename'])
            ->joinLeft(['pc' => $this->getTableName()], 'pc.thread_id=p.thread_id', new \Zend_Db_Expr('COUNT(DISTINCT pc.id) AS postcount'))
            ->where('p.id=?', $postid);

        return $this->getAdapter()->fetchRow($query);
    }

    /**
     * @param $postid
     *
     * @return bool
     */
    public function deletePost($postid)
    {
        return ($this->delete($this->getAdapter()->quoteInto('id=?', $postid)) > 0);
    }
}
