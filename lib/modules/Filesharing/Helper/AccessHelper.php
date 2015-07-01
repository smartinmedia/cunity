<?php

namespace Cunity\Filesharing\Helper;

use Cunity\Core\Helper\UserHelper;
use Cunity\Core\Request\Session;
use Cunity\Filesharing\Models\Db\Table\Files;

/**
 * Class AccessHelper
 * @package Cunity\Filesharing\Helper
 */
class AccessHelper
{
    /**
     * @param $fileId
     * @param null $userId
     *
     * @return bool
     */
    public static function canRead($fileId, $userId = null)
    {
        if (UserHelper::isAdmin()) {
            return true;
        }

        $files = new Files();
        $fileList = $files->listFiles($userId)->toArray();

        $canRead = false;

        foreach ($fileList as $file) {
            if ($fileId == $file['id']) {
                $canRead = true;
            }
        }

        return $canRead;
    }

    /**
     * @param $fileId
     * @param null $userId
     *
     * @return bool
     */
    public static function canDelete($fileId, $userId = null)
    {
        if (UserHelper::isAdmin()) {
            return true;
        }

        if ($userId === null) {
            $userId = Session::get('user')->userid;
        }

        $files = new Files();
        $file = $files->fetchAll('id = '.$fileId.' and user_id != '.$userId);

        return ($file->count() === 1);
    }
}
