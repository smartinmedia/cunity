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

namespace Cunity\Gallery\Models\Db\Row;

use Cunity\Comments\Models\Db\Table\Comments;
use Cunity\Core\Cunity;
use Cunity\Core\Request\Session;
use Cunity\Gallery\Models\Db\Table\GalleryAlbums;
use Cunity\Likes\Models\Db\Table\Likes;
use Cunity\Newsfeed\Models\Db\Table\Posts;

/**
 * Class Image.
 */
class Image extends \Zend_Db_Table_Row_Abstract
{
    /**
     * @return bool
     *
     * @throws \Exception
     * @throws \Zend_Db_Table_Exception
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function deleteImage()
    {
        if ($this->owner_id == Session::get('user')->userid && $this->owner_type === null) {
            $albums = new GalleryAlbums();
            $album = $albums->find($this->albumid);
            $album->current()->removeImage($this->id);
            $settings = Cunity::get('settings');
            $likes = new Likes();
            $comments = new Comments();
            $posts = new Posts();
            $posts->delete([$posts->getAdapter()->quote('type=`image`'), $posts->getAdapter()->quoteInto('content=?', $this->id)]);
            $comments->delete($this->_getTable()->getAdapter()->quoteInto("ref_id=? AND ref_name='image'", $this->id));
            $likes->delete($this->_getTable()->getAdapter()->quoteInto("ref_id=? AND ref_name='image'", $this->id));
            $filename = '../data/uploads/'.$settings->getSetting('core.filesdir').'/'.$this->filename;
            $filenameThumb = '../data/uploads/'.$settings->getSetting('core.filesdir').'/thumb_'.$this->filename;
            $filenameCr = '../data/uploads/'.$settings->getSetting('core.filesdir').'/cr_'.$this->filename;

            if (file_exists($filename)) {
                unlink($filename);
            }

            if (file_exists($filenameThumb)) {
                unlink($filenameThumb);
            }

            if (file_exists($filenameCr)) {
                unlink($filenameCr);
            }

            return ($this->delete() == 1);
        }

        return false;
    }
}
