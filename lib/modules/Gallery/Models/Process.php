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

namespace Cunity\Gallery\Models;

use Cunity\Comments\Models\Db\Table\Comments;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\View\Ajax\View;
use Cunity\Core\View\Message;
use Cunity\Core\View\PageNotFound;
use Cunity\Gallery\Models\Db\Table\GalleryAlbums;
use Cunity\Gallery\Models\Db\Table\GalleryImages;
use Cunity\Gallery\View\Album;
use Cunity\Likes\Models\Db\Table\Likes;

/**
 * Class Process
 * @package Cunity\Gallery\Models
 */
class Process
{

    /**
     * @param $action
     */
    public function __construct($action)
    {
        if (method_exists($this, $action)) {
            call_user_func([$this, $action]);
        }
    }

    /**
     * @throws \Zend_Db_Table_Exception
     */
    public function deleteAlbum()
    {
        $albums = new GalleryAlbums();
        $album = $albums->find($_POST['albumid'])->current();
        $view = new View($album->deleteAlbum());
        $view->sendResponse();
    }

    /**
     *
     */
    private function overview()
    {
        $table = new GalleryAlbums();
        $albums = $table->loadAlbums($_POST['userid']);
        if ($albums !== null) {
            $view = new View(true);
            $view->addData(["result" => $albums]);
            $view->sendResponse();
        } else {
            new Message("Sorry", "We can't find any albums!", "danger");
        }
    }

    /**
     *
     */
    private function create()
    {
        $table = new GalleryAlbums();
        if (($_POST['privacy'] == 0)) {
            $result = $table->insert([
                "title" => $_POST['title'],
                "description" => $_POST['description'],
                "owner_id" => $_SESSION['user']->userid,
                "type" => "shared",
                "user_upload" => isset($_POST['allow_upload']) ? 1 : 0,
                "privacy" => $_POST['privacy']
            ]);
        } else {
            $result = $table->insert([
                "title" => $_POST['title'],
                "description" => $_POST['description'],
                "owner_id" => $_SESSION['user']->userid,
                "type" => null,
                "user_upload" => isset($_POST['allow_upload']) ? 1 : 0,
                "privacy" => $_POST['privacy']
            ]);
        }
        $view = new View($result !== null);
        $view->addData(["target" => Url::convertUrl("index.php?m=gallery&action=" . $result . "&x=" . str_replace(" ", "_", $_POST['title']))]);
        $view->sendResponse();
    }

    /**
     * @throws \Zend_Db_Table_Exception
     */
    private function edit()
    {
        $table = new GalleryAlbums();
        $album = $table->find($_POST['albumid'])->current();
        $result = $album->update($_POST);
        $view = new View();
        $view->setStatus($result !== null);
        $view->sendResponse();
    }

    /**
     * @throws \Zend_Db_Table_Exception
     */
    private function upload()
    {
        $albums = new GalleryAlbums();
        $images = new GalleryImages();
        if (isset($_POST['newsfeed_post'])) {
            $album = $albums->fetchRow($albums->select()->where("type=?", "newsfeed")->where("owner_id=?", $_SESSION['user']->userid)->where("owner_type IS NULL"));
            if ($album === null) {
                $albumid = $albums->newNewsfeedAlbums($_SESSION['user']->userid);
                $album = $albums->fetchRow($albums->select()->where("id=?", $albumid));
            }
        } else {
            $album = $albums->find($_POST['albumid'])->current();
        }
        $result = $images->uploadImage($album->id, isset($_POST['newsfeed_post']));
        $album->addImage((isset($_POST['newsfeed_post'])) ? $result['content'] : $result['imageid']);
        $view = new View($result !== false);
        $view->addData($result);
        $view->sendResponse();
    }

    /**
     * @throws \Zend_Db_Table_Exception
     */
    private function deleteImage()
    {
        $images = new GalleryImages();
        $image = $images->find($_POST['imageid'])->current();
        $view = new View();
        if (($image !== null)) {
            $view->setStatus($image->deleteImage());
        } else {
            $view->setStatus(false);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadImage()
    {
        $id = $_POST['id'];
        $images = new GalleryImages();
        $albums = new GalleryAlbums();
        $result = $images->getImageData($id);
        $view = new View(true);
        if ($result !== null) {
            $result = $result[0];
            $albumData = $albums->getAlbumData($result['albumid']);
            $likeTable = new Likes();
            $socialData['likes'] = $likeTable->getLikes($id, "image");
            $socialData['dislikes'] = $likeTable->getLikes($id, "image", 1);

            if ($result['commentcount'] > 0) {
                $comments = new Comments();
                $socialData['comments'] = $comments->get($id, "image", false, 13);
            } else {
                $socialData['comments'] = [];
            }
            $view->addData(array_merge($socialData, $result, ["album" => $albumData]));
        } else {
            $view->setStatus(false);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadImages()
    {
        $images = new GalleryImages();
        $result = $images->getImages($_POST['albumid'], ["limit" => $_POST['limit'], "offset" => $_POST['offset']]);
        $view = new View($result !== false);
        $view->addData(["result" => $result]);
        $view->sendResponse();
    }

    /**
     * @throws \Cunity\Core\Exception
     */
    private function loadAlbum()
    {
        $albums = new GalleryAlbums();
        $album = $albums->getAlbumData($_GET['action']);
        if ($album !== false) {
            $view = new Album();
            $view->setMetaData(["title" => $album['title'], "description" => $album['description']]);
            $view->assign("album", $album);
            if ($album->owner_id == $_SESSION['userid'] && $album->owner_type === null) {
                $view->registerScript("gallery", "album-edit");
            }
            $view->show();
        } else {
            new PageNotFound();
        }
    }
}
