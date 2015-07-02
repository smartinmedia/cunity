<?php

namespace Cunity\Filesharing\Models\Db\Table;

use Cunity\Core\Helper\UserHelper;
use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\Request\Session;
use Cunity\Friends\Models\Db\Table\Relationships;

/**
 * Class Files.
 */
class Files extends Table
{
    /**
     * @var string
     */
    protected $_name = 'filesharing_files';

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function addFile(array $data)
    {
        return $this->insert($data);
    }

    /**
     * @param $fileId
     *
     * @return bool
     */
    public function removeFile($fileId)
    {
        return (0 < $this->delete($this->getAdapter()->quoteInto('id=?', $fileId)));
    }

    /**
     * @param null $userid
     *
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function listFiles($userid = null)
    {
        if ($userid === null) {
            $userid = Session::get('user')->userid;
        }

        if (UserHelper::isAdmin()) {
            $fileList = $this->fetchAll();
        } else {
            $friends = new Relationships();
            $friendList = $friends->getFullFriendList();
            $friendIds = [];

            foreach ($friendList as $friend) {
                $friendIds[] = $friend['userid'];
            }

            $fileRights = new FileRights();
            $rights = $fileRights->fetchAll('user_id in ('.implode(',', $friendIds).')');
            $fileIds = [];

            foreach ($rights as $right) {
                $fileIds[] = $right['file_id'];
            }

            $fileIds = array_values(array_unique($fileIds));

            $fileList = $this->fetchAll('user_id = '.$userid.' or id in ('.implode(',', $fileIds).')');
        }

        return $fileList;
    }

    /**
     * @param null $userid
     *
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function listOwnFiles($userid = null)
    {
        if ($userid === null) {
            $userid = Session::get('user')->userid;
        }

        return $this->fetchAll('user_id = '.$userid);
    }
}
