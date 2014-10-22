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

namespace Cunity\Profile\Models;

use Cunity\Core\Cunity;
use Cunity\Core\Models\Db\Row\User;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\Models\Validation\Email;
use Cunity\Core\Models\Validation\Username;
use Cunity\Core\View\Ajax\View;
use Cunity\Core\View\Message;
use Cunity\Core\View\PageNotFound;
use Cunity\Gallery\Models\Db\Table\GalleryImages;
use Cunity\Notifications\Models\Db\Table\NotificationSettings;
use Cunity\Profile\Models\Db\Table\ProfileFields;
use Cunity\Profile\View\ProfileCrop;
use Skoch\Filter\File\Crop;

/**
 * Class ProfileEdit
 * @package Profile\Models
 */
class ProfileEdit
{

    /**
     * @var User
     */
    private $user = null;

    /**
     *
     */
    public function __construct()
    {
        $this->user = $_SESSION['user'];
        $this->handleRequest();
    }

    /**
     *
     */
    private function handleRequest()
    {
        if ($_GET['action'] == "cropImage") {
            $this->cropImage();
        } elseif (isset($_POST['edit']) && !empty($_POST['edit'])) {
            if (method_exists($this, $_POST['edit'])) {
                call_user_func([$this, $_POST['edit']]);
            }
        } else {
            $view = new \Cunity\Profile\View\ProfileEdit();
            /** @noinspection PhpUndefinedMethodInspection */
            $user = $this->user->getTable()->get($_SESSION['user']->userid);
            /** @var User $user */
            $profile = $user->toArray(["userid", "username", "email", "firstname", "lastname", "registered", "pimg", "timg", "palbumid", "talbumid"]);
            $table = new Db\Table\Privacy();
            $privacy = $table->getPrivacy();
            $table = new NotificationSettings();
            $notificationSettings = $table->getSettings();
            $profileFields = new ProfileFields();
            $view->assign('profileFields', $profileFields->getAll());
            $view->assign("profile", array_merge($profile, ["privacy" => $privacy, 'notificationSettings' => $notificationSettings]));
            $view->render();
        }
    }

    /**
     * @throws \Exception
     */
    private function cropImage()
    {
        if (!isset($_GET['x']) || empty($_GET['x'])) {
            new PageNotFound();
        }
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        $images = new \Cunity\Gallery\Models\Db\Table\GalleryImages();
        $result = $images->getImageData($_GET['x']);
        $view = new ProfileCrop();
        /** @noinspection PhpUndefinedMethodInspection */
        $user = $_SESSION['user']->getTable()->get($_SESSION['user']->userid); // Get a new user Object with all image-data
        /** @var User $user */
        $profileData = $user->toArray(["userid", "username", "name", "timg", "pimg", "talbumid", "palbumid"]);
        $view->assign(["profile" => $profileData, "result" => $result[0], "type" => $_GET['y'], "image" => getimagesize("../data/uploads/" . Cunity::get("settings")->getSetting("core.filesdir") . "/" . $result[0]['filename'])]);
        $view->show();
    }

    /**
     *
     */
    public function deleteImage()
    {
        if ($_POST['type'] == "profile") {
            $_SESSION['user']->profileImage = 0;
        } else {
            $_SESSION['user']->titleImage = 0;
        }
        /** @noinspection PhpUndefinedMethodInspection */
        if ($_SESSION['user']->save()) {
            $view = new View(true);
            $view->sendResponse();
        }
    }

    /**
     * @throws \Zend_Db_Table_Exception
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function loadPinData()
    {
        $pinid = $_POST['id'];
        $pins = new Db\Table\ProfilePins();
        $data = $pins->find($pinid)->current()->toArray();
        $data['content'] = htmlspecialchars_decode($data['content']);
        $view = new View($data !== null);
        $view->addData($data);
        $view->sendResponse();
    }

    /**
     *
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function deletePin()
    {
        if (isset($_POST['id'])) {
            $pins = new Db\Table\ProfilePins();
            $result = $pins->delete($pins->getAdapter()->quoteInto("id=?", $_POST['id']));
            $view = new View(($result > 0));
            $view->addData(["id" => $_POST['id']]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function pin()
    {
        if (isset($_POST['title']) && isset($_POST['type']) && isset($_POST['content'])) {
            $pins = new Db\Table\ProfilePins();
            if (isset($_POST['editPin'])) {
                $pins->update(["title" => $_POST['title'], "content" => $_POST['content'], "type" => $_POST['type'], "iconclass" => $_POST['iconClass']], $pins->getAdapter()->quoteInto("id=?", $_POST['editPin']));
                $res = $_POST['editPin'];
            } else {
                $res = $pins->insert(["userid" => $this->user->userid, "title" => $_POST['title'], "content" => $_POST['content'], "type" => $_POST['type'], "iconclass" => $_POST['iconClass']]);
            }
            $view = new View(true);
            $view->addData(["title" => $_POST['title'], "type" => $_POST['type'], "content" => htmlspecialchars_decode($_POST['content']), "iconclass" => $_POST['iconClass'], "id" => $res, "updated" => isset($_POST['editPin'])]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function notifications()
    {
        if (!empty($_POST['types'])) {
            $result = [];
            foreach ($_POST['types'] as $key => $v) {
                if (isset($_POST['alert'][$key]) && isset($_POST['mail'][$key])) {
                    $result[$key] = 3;
                } elseif (isset($_POST['alert'][$key]) && !isset($_POST['mail'][$key])) {
                    $result[$key] = 1;
                } elseif (!isset($_POST['alert'][$key]) && isset($_POST['mail'][$key])) {
                    $result[$key] = 2;
                } else {
                    $result[$key] = 0;
                }
            }
            $settings = new NotificationSettings();
            $res = $settings->updateSettings($result);
            $view = new View($res);

            if ($res) {
                $message = $view->translate("Notification settings changed successfully!");
            } else {
                $message = $view->translate("Sorry, something went wrong, try again");
            }

            $view->addData(['msg' => $message]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function pinPositions()
    {
        $pins = new Db\Table\ProfilePins();
        if (isset($_POST['pins'])) {
            foreach ($_POST['pins'] as $i => $pin) {
                $pins->updatePosition($_POST['column'], $i, $pin);
            }
        }
    }

    /**
     *
     */
    private function general()
    {
        $view = new View();
        $message = [];
        $validateMail = new Email();
        $validateUsername = new Username();

        if ($validateUsername->isValid($_POST['username'])) {
            $this->user->username = $_POST['username'];
        } else {
            $message[] = implode(",", $validateUsername->getMessages());
        }
        if ($validateMail->isValid($_POST['email'])) {
            $this->user->email = $_POST['email'];
        } else {
            $message[] = implode(",", $validateMail->getMessages());
        }
        $res = $this->user->save();
        if (!$res) {
            $message[] = $view->translate("Something went wrong! Please try again later!");
        }
        $view->setStatus(empty($message));
        if (empty($message)) {
            $message[] = $view->translate("Your changes were saved successfully!");
        }
        $view->addData(["msg" => implode(',', $message)]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function changePassword()
    {
        $status = false;
        $view = new View();
        if (sha1($_POST['old-password'] . $this->user->salt) === $this->user->password) {
            if ($_POST['new-password'] === $_POST['new-password-rep']) {
                $this->user->password = sha1($_POST['new-password'] . $this->user->salt);
                $this->user->save();
                $status = true;
                $message = $view->translate("Password changed successfully!");
            } else {
                $message = $view->translate("The new passwords do not match!");
            }
        } else {
            $message = $view->translate("The current password is wrong");
        }
        $view->setStatus($status);
        $view->addData(["msg" => $message]);
        $view->sendResponse();
    }

    /**
     *
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function changePrivacy()
    {
        if (isset($_POST['privacy']) && is_array($_POST['privacy'])) {
            $table = new Db\Table\Privacy();
            $res = $table->updatePrivacy($_SESSION['user']->userid, $_POST['privacy']);
            $view = new View();
            $view->setStatus($res);

            if ($res) {
                $message = $view->translate("Privacy settings changed successfully!");
            } else {
                $message = $view->translate("Sorry, something went wrong, try again");
            }

            $view->addData(['msg' => $message]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function changeimage()
    {
        $gimg = new GalleryImages();
        $result = $gimg->uploadProfileImage();
        if ($result !== false) {
            $view = new View(true);
            $view->addData($result);
            $view->sendResponse();
        } else {
            new Message("Sorry!", "Something went wrong on our server!");
        }
    }

    /**
     * @throws \Exception
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function crop()
    {
        $file = new Crop([
            "x" => $_POST['crop-x'],
            "y" => $_POST['crop-y'],
            "x1" => $_POST['crop-x1'],
            "y1" => $_POST['crop-y1'],
            "thumbwidth" => ($_POST['type'] == "title") ? 970 : 150,
            "directory" => "../data/uploads/" . Cunity::get("settings")->getSetting("core.filesdir"),
            "prefix" => "cr_"
        ]);
        $file->filter($_POST['crop-image']);
        if ($_POST['type'] == "title") {
            $_SESSION['user']->titleImage = $_POST['imageid'];
        } else {
            $_SESSION['user']->profileImage = $_POST['imageid'];
        }
        /** @noinspection PhpUndefinedMethodInspection */
        if ($_SESSION['user']->save()) {
            header("Location: " . Url::convertUrl("index.php?m=profile"));
        }
    }
}
