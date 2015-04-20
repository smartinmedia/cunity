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
use Cunity\Gallery\Models\Db\Table\GalleryImages;
use Cunity\Likes\Models\Db\Table\Likes;

/**
 * Class Album.
 */
class Album extends \Zend_Db_Table_Row_Abstract
{
    /**
     * @return bool
     *
     * @throws \Exception
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function deleteAlbum()
    {
        $images = new GalleryImages();
        $imageslist = $images->getImages($this->id);
        $settings = Cunity::get('settings');
        foreach ($imageslist as $image) {
            $likes = new Likes();
            $comments = new Comments();

            $comments->delete($this->getTable()->getAdapter()->quoteInto("ref_id=? AND ref_name='image'", $image['id']));
            $likes->delete($this->getTable()->getAdapter()->quoteInto("ref_id=? AND ref_name='image'", $image['id']));
            unlink('../data/uploads/'.$settings->getSetting('core.filesdir').'/'.$image['filename']);
            unlink('../data/uploads/'.$settings->getSetting('core.filesdir').'/thumb_'.$image['filename']);
            if (file_exists('../data/uploads/'.$settings->getSetting('core.filesdir').'/cr_'.$image['filename'])) {
                unlink('../data/uploads/'.$settings->getSetting('core.filesdir').'/cr_'.$image['filename']);
            }
        }
        $images->delete($images->getAdapter()->quoteInto('albumid=?', $this->id));

        return (0 < $this->delete());
    }

    /**
     * @param array $updates
     *
     * @return mixed
     *
     * @throws \Zend_Db_Table_Row_Exception
     */
    public function update(array $updates)
    {
        foreach ($updates as $field => $value) {
            if (isset($this->_data[$field])) {
                $this->__set($field, $value);
            }
        }

        return $this->save();
    }

    /**
     * @return array
     */
    public function getImages()
    {
        $images = new GalleryImages();

        return $images->getImages($this->id);
    }

    /**
     * @param $imageid
     *
     * @return mixed
     */
    public function addImage($imageid)
    {
        $this->photo_count++;
        $this->time = new \Zend_Db_Expr('UTC_TIMESTAMP()');
        $this->cover = $imageid;

        return $this->save();
    }

    /**
     * @param $imageid
     *
     * @return mixed
     */
    public function removeImage($imageid)
    {
        if ($this->cover == $imageid) {
            $this->cover = $this->getLastImageId();
        };
        $this->photo_count--;

        return $this->save();
    }

    /**
     * @return int|string
     */
    private function getLastImageId()
    {
        $images = new GalleryImages();
        $res = $images->fetchRow($images->select()->where('albumid=?', $this->id)->order('time')->limit(1));
        if ($res === null) {
            return 0;
        } else {
            return $res->id;
        }
    }
}
