<?php

namespace Gallery\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;
use Gallery\Models\Uploader;
use Newsfeed\Models\Db\Table\Posts;

/**
 * Class Gallery_Images
 * @package Gallery\Models\Db\Table
 */
class Gallery_Images extends Table {

    /**
     * @var string
     */
    protected $_name = 'gallery_images';

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     * @var string
     */
    protected $_rowClass = "\Gallery\Models\Db\Row\Image";

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param $albumid
     * @param array $limit
     * @return array
     */
    public function getImages($albumid, array $limit = []) {
        $query = $this->getAdapter()->select()->from(["i" => $this->_dbprefix . "gallery_images"])
                ->joinLeft(["co" => $this->_dbprefix . "comments"], "co.ref_id = i.id AND co.ref_name = 'image'", "COUNT(DISTINCT co.id) AS comments")
                ->joinLeft(["li" => $this->_dbprefix . "likes"], "li.ref_id = i.id AND li.ref_name = 'image' AND li.dislike = 0", "COUNT(DISTINCT li.id) AS likes")
                ->joinLeft(["di" => $this->_dbprefix . "likes"], "di.ref_id = i.id AND di.ref_name = 'image' AND di.dislike = 1", "COUNT(DISTINCT di.id) AS dislikes")
                ->joinLeft(["ld" => $this->_dbprefix . "likes"], "ld.ref_id = i.id AND ld.ref_name = 'image' AND ld.userid = " . $this->getAdapter()->quote($_SESSION['user']->userid), "ld.dislike AS ownlike")
                ->where("i.albumid=?", $albumid)
                ->group("i.id");
        if (!empty($limit)) {
            $query->limit($limit['limit'], $limit['offset']);
        }
        $result = $this->getAdapter()->fetchAll($query);
        return $result;
    }

    /**
     * @param $imageid
     * @return array
     */
    public function getImageData($imageid) {
        return $this->getAdapter()->fetchAll(
                        $this->getAdapter()->select()->from(["i" => $this->_dbprefix . "gallery_images"])
                                ->joinLeft(["u" => $this->_dbprefix . "users"], "u.userid=i.owner_id AND i.owner_type IS NULL", ["username", "name"])
                                ->joinLeft(["ci" => $this->_dbprefix . "gallery_images"], "ci.id=u.profileImage", ["profileImage" => "filename"])
                                ->joinLeft(["co" => $this->_dbprefix . "comments"], "co.ref_id = i.id AND co.ref_name = 'image'", "COUNT(DISTINCT co.id) AS commentcount")
                                ->joinLeft(["li" => $this->_dbprefix . "likes"], "li.ref_id = i.id AND li.ref_name = 'image' AND li.dislike = 0", "COUNT(DISTINCT li.id) AS likescount")
                                ->joinLeft(["di" => $this->_dbprefix . "likes"], "di.ref_id = i.id AND di.ref_name = 'image' AND di.dislike = 1", "COUNT(DISTINCT di.id) AS dislikescount")
                                ->joinLeft(["ld" => $this->_dbprefix . "likes"], "ld.ref_id = i.id AND ld.ref_name = 'image' AND ld.userid = " . $this->getAdapter()->quote($_SESSION['user']->userid), "ld.dislike AS ownlike")
                                ->where("i.id=?", $imageid)
                                ->group("i.id"));
    }

    /**
     * @param $albumid
     * @param bool $newsfeed_post
     * @param array $uploader_data
     * @return array|bool|mixed
     */
    public function uploadImage($albumid, $newsfeed_post = false, $uploader_data = []) {
        if (isset($_FILES) && isset($_FILES['file']) && $albumid > 0) {
            $uploader = new Uploader();
            $file = $uploader->upload($albumid . sha1($_SESSION['user']->userhash . time()) . rand());
            if (empty($uploader_data)) {
                list($owner_id, $owner_type) = [$_SESSION['user']->userid, NULL];
            } else {
                list($owner_id, $owner_type) = $uploader_data;
            }
            $imageid = $this->insert(["owner_id" => $owner_id, "owner_type" => $owner_type, "albumid" => $albumid, "filename" => $file, "caption" => (!empty($_POST['content'])) ? $_POST['content'] : ""]);
            if ($newsfeed_post) {
                $posts = new Posts();
                return $posts->post([
                            "wall_owner_id" => $_POST['wall_owner_id'],
                            "wall_owner_type" => $_POST['wall_owner_type'],
                            "wall_id" => $_POST['wall_id'],
                            "privacy" => $_POST['privacy'],
                            "userid" => $owner_id,
                            "content" => $imageid,
                            "type" => "image"
                ]);
            }
            return ["filename" => $file, "imageid" => $imageid];
        }
        return false;
    }

    /**
     * @return array|bool|mixed
     */
    public function uploadProfileImage() {
        $albums = new Gallery_Albums();
        $res = $albums->fetchRow($albums->select()->where("type=?", "profile")->where("owner_type IS NULL")->where("owner_id=?", $_SESSION['user']->userid));
        if ($res === NULL) {
            $albumid = $albums->newProfileAlbums($_SESSION['user']->userid);
            $res = $albums->fetchRow($albums->select()->where("type=?", "profile")->where("owner_type IS NULL")->where("owner_id=?", $_SESSION['user']->userid));
        } else {
            $albumid = $res->id;
        }
        $result = $this->uploadImage($albumid);
        $res->addImage($result['imageid']);
        if (is_array($result)) {
            return $result;
        }
        return false;
    }

    /**
     * @param $eventid
     * @return array|bool|mixed
     */
    public function uploadEventImage($eventid) {
        $albums = new Gallery_Albums();
        $res = $albums->fetchRow($albums->select()->where("type=?", "event")->where("owner_type = 'event'")->where("owner_id=?", $eventid));
        $result = $this->uploadImage($res->id, false, [$eventid, "event"]);
        $res->addImage($result['imageid']);
        if (is_array($result))
            return $result;
        return false;
    }

}
