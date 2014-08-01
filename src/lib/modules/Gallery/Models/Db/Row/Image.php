<?php

namespace Gallery\Models\Db\Row;

use Comments\Models\Db\Table\Comments;
use Core\Cunity;
use Gallery\Models\Db\Table\Gallery_Albums;
use Likes\Models\Db\Table\Likes;
use Newsfeed\Models\Db\Table\Posts;

/**
 * Class Image
 * @package Gallery\Models\Db\Row
 */
class Image extends \Zend_Db_Table_Row_Abstract {

    /**
     * @return bool
     * @throws \Exception
     * @throws \Zend_Db_Table_Exception
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function deleteImage() {
        if ($this->owner_id == $_SESSION['user']->userid && $this->owner_type == NULL) {
            $albums = new Gallery_Albums();
            $album = $albums->find($this->albumid);
            $album->current()->removeImage($this->id);
            $settings = Cunity::get("settings");
            $likes = new Likes();
            $comments = new Comments();
            $posts = new Posts;
            $posts->delete([$posts->getAdapter()->quote("type=`image`"), $posts->getAdapter()->quoteInto("content=?", $this->id)]);
            $comments->delete($this->_getTable()->getAdapter()->quoteInto("ref_id=? AND ref_name='image'", $this->id));
            $likes->delete($this->_getTable()->getAdapter()->quoteInto("ref_id=? AND ref_name='image'", $this->id));
            @unlink("../data/uploads/" . $settings->getSetting("core.filesdir") . "/" . $this->filename);
            @unlink("../data/uploads/" . $settings->getSetting("core.filesdir") . "/thumb_" . $this->filename);
            if (file_exists("../data/uploads/" . $settings->getSetting("core.filesdir") . "/cr_" . $this->filename)) {
                unlink("../data/uploads/" . $settings->getSetting("core.filesdir") . "/cr_" . $this->filename);
            }
            return ($this->delete() == 1);
        }
        return false;
    }

}
