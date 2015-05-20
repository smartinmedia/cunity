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

namespace Cunity\Profile\Models;

use Cunity\Core\Cunity;
use Cunity\Core\Exceptions\PageNotFound;
use Cunity\Core\Models\Db\Row\User;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\Models\Validation\Email;
use Cunity\Core\Models\Validation\Username;
use Cunity\Core\Request\Get;
use Cunity\Core\Request\Post;
use Cunity\Core\View\Message;
use Cunity\Core\View\View;
use Cunity\Gallery\Models\Db\Table\GalleryImages;
use Cunity\Notifications\Models\Db\Table\NotificationSettings;
use Cunity\Profile\Models\Db\Table\ProfileFields;
use Cunity\Profile\View\ProfileCrop;
use Skoch\Filter\File\Crop;

/**
 * Class ProfileEdit.
 */
class ProfileEdit
{
    /**
     * @var array
     */
    protected $message;

    /**
     * @var User
     */
    protected $user = null;

    /**
     * @param User $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
        $this->handleRequest();
    }

    /**
     *
     */
    private function handleRequest()
    {
        if (Get::get('action') == 'cropImage') {
            $this->cropImage();
        } elseif (Post::get('edit') !== null && Post::get('edit') !== '') {
            if (method_exists($this, Post::get('edit'))) {
                call_user_func([$this, Post::get('edit')]);
            }
        } else {
            $this->updateProfileFields();
        }
    }

    /**
     * @throws \Exception
     */
    private function cropImage()
    {
        if (Get::get('x') === null || Get::get('x') === '') {
            throw new PageNotFound();
        }
        $images = new \Cunity\Gallery\Models\Db\Table\GalleryImages();
        $result = $images->getImageData(Get::get('x'));
        $view = new ProfileCrop();
        $user = $this->user->getTable()->get($this->user->userid); // Get a new user Object with all image-data
        /* @var User $user */
        $profileData = $user->toArray(['userid', 'username', 'name', 'timg', 'pimg', 'talbumid', 'palbumid']);
        $view->assign(['profile' => $profileData, 'result' => $result[0], 'type' => Get::get('y'), 'image' => getimagesize('../data/uploads/'.Cunity::get('settings')->getSetting('core.filesdir').'/'.$result[0]['filename'])]);
        $view->show();
    }

    /**
     *
     */
    public function deleteImage()
    {
        if (Post::get('type') === 'profile') {
            $this->user->profileImage = 0;
        } else {
            $this->user->titleImage = 0;
        }
        if ($this->user->save()) {
            $view = new \Cunity\Core\View\Ajax\View(true);
            $view->sendResponse();
        }
    }

    /**
     * @throws \Zend_Db_Table_Exception
     */
    private function loadPinData()
    {
        $pinid = Post::get('id');
        $pins = new Db\Table\ProfilePins();
        $data = $pins->find($pinid)->current()->toArray();
        $data['content'] = htmlspecialchars_decode($data['content']);
        $view = new \Cunity\Core\View\Ajax\View($data !== null);
        $view->addData($data);
        $view->sendResponse();
    }

    /**
     *
     */
    private function deletePin()
    {
        if (Post::get('id') !== null) {
            $pins = new Db\Table\ProfilePins();
            $result = $pins->delete($pins->getAdapter()->quoteInto('id=?', Post::get('id')));
            $view = new \Cunity\Core\View\Ajax\View($result > 0);
            $view->addData(['id' => Post::get('id')]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function pin()
    {
        if (Post::get('title') !== null && Post::get('type') !== null && Post::get('content') !== null) {
            $pins = new Db\Table\ProfilePins();
            if (Post::get('editPin') !== null) {
                $pins->update(['title' => Post::get('title'), 'content' => Post::get('content'), 'type' => Post::get('type'), 'iconclass' => Post::get('iconClass')], $pins->getAdapter()->quoteInto('id=?', Post::get('editPin')));
                $res = Post::get('editPin');
            } else {
                $res = $pins->insert(['userid' => $this->user->userid, 'title' => Post::get('title'), 'content' => Post::get('content'), 'type' => Post::get('type'), 'iconclass' => Post::get('iconClass')]);
            }
            $view = new \Cunity\Core\View\Ajax\View(true);
            $view->addData(['title' => Post::get('title'), 'type' => Post::get('type'), 'content' => htmlspecialchars_decode(Post::get('content')), 'iconclass' => Post::get('iconClass'), 'id' => $res, 'updated' => (Post::get('editPin') !== null)]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function notifications()
    {
        if (Post::get('types') !== '') {
            $result = [];
            $result = $this->generateResult($result);
            $settings = new NotificationSettings();
            $res = $settings->updateSettings($result);
            $view = new \Cunity\Core\View\Ajax\View($res);

            if ($res) {
                $message = $view->translate('Notification settings changed successfully!');
            } else {
                $message = $view->translate('Sorry, something went wrong, try again');
            }

            $view->addData(['msg' => $message]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function pinPositions()
    {
        $pins = new Db\Table\ProfilePins();
        if (Post::get('pins') !== null) {
            foreach (Post::get('pins') as $i => $pin) {
                $pins->updatePosition(Post::get('column'), $i, $pin);
            }
        }
    }

    /**
     *
     */
    private function general()
    {
        $view = new \Cunity\Core\View\Ajax\View();
        $this->message = [];
        $this->getUserName(new Username());
        $this->validateEmail(new Email());
        $this->user->firstname = Post::get('firstname');
        $this->user->lastname = Post::get('lastname');
        $this->user->name = Post::get('firstname').' '.Post::get('lastname');

        $res = $this->user->save();

        if (!$res) {
            $this->message[] = $view->translate('Something went wrong! Please try again later!');
        }

        $view->setStatus(empty($this->message));

        if (empty($this->message)) {
            $this->message[] = $view->translate('Your changes were saved successfully!');
        }

        $view->addData(['msg' => implode(',', $this->message)]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function changePassword()
    {
        $status = false;
        $view = new \Cunity\Core\View\Ajax\View();
        if (sha1(Post::get('old-password').$this->user->salt) === $this->user->password) {
            if (Post::get('new-password') === Post::get('new-password-rep')) {
                $this->user->password = sha1(Post::get('new-password').$this->user->salt);
                $this->user->save();
                $status = true;
                $message = $view->translate('Password changed successfully!');
            } else {
                $message = $view->translate('The new passwords do not match!');
            }
        } else {
            $message = $view->translate('The current password is wrong');
        }
        $view->setStatus($status);
        $view->addData(['msg' => $message]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function changePrivacy()
    {
        if (Post::get('privacy') !== null && is_array(Post::get('privacy'))) {
            $table = new Db\Table\Privacy();
            $res = $table->updatePrivacy($this->user->userid, Post::get('privacy'));
            $view = new \Cunity\Core\View\Ajax\View();
            $view->setStatus($res);

            if ($res) {
                $message = $view->translate('Privacy settings changed successfully!');
            } else {
                $message = $view->translate('Sorry, something went wrong, try again');
            }

            $view->addData(['msg' => $message]);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function changeimage()
    {
        $gimg = new GalleryImages();
        $result = $gimg->uploadProfileImage();
        if ($result !== false) {
            $view = new \Cunity\Core\View\Ajax\View(true);
            $view->addData($result);
            $view->sendResponse();
        } else {
            new Message('Sorry!', 'Something went wrong on our server!');
        }
    }

    /**
     * @throws \Exception
     */
    private function crop()
    {
        $file = new Crop([
            'x' => Post::get('crop-x'),
            'y' => Post::get('crop-y'),
            'x1' => Post::get('crop-x1'),
            'y1' => Post::get('crop-y1'),
            'thumbwidth' => (Post::get('type') == 'title') ? 970 : 150,
            'directory' => '../data/uploads/'.Cunity::get('settings')->getSetting('core.filesdir'),
            'prefix' => 'cr_',
        ]);
        $file->filter(Post::get('crop-image'));
        if (Post::get('type') == 'title') {
            $this->user->titleImage = Post::get('imageid');
        } else {
            $this->user->profileImage = Post::get('imageid');
        }
        if ($this->user->save()) {
            header('Location: '.Url::convertUrl('index.php?m=profile'));
        }
    }

    /**
     *
     */
    private function updateProfileFields()
    {
        $view = new \Cunity\Profile\View\ProfileEdit();
        $user = $this->user->getTable()->get($this->user->userid);
        /* @var User $user */
        $profile = $user->toArray(['userid', 'username', 'email', 'firstname', 'lastname', 'registered', 'pimg', 'timg', 'palbumid', 'talbumid']);
        $table = new Db\Table\Privacy();
        $privacy = $table->getPrivacy();
        $table = new NotificationSettings();
        $notificationSettings = $table->getSettings();
        $profileFields = new ProfileFields();
        $view->assign('profileFields', $profileFields->getAll());
        $view->assign('profile', array_merge($profile, ['privacy' => $privacy, 'notificationSettings' => $notificationSettings]));
        $view->render();
    }

    /**
     * @param Username $validateUsername
     */
    private function getUserName(Username $validateUsername)
    {
        if ($validateUsername->isValid(Post::get('username'))) {
            $this->user->username = Post::get('username');
        } else {
            $this->message[] = implode(',', $validateUsername->getMessages());
        }
    }

    /**
     * @param Email $validateMail
     */
    private function validateEmail(Email $validateMail)
    {
        if ($validateMail->isValid(Post::get('email'))) {
            $this->user->email = Post::get('email');
        } else {
            $this->message[] = implode(',', $validateMail->getMessages());
        }
    }

    /**
     * @param $result
     *
     * @return mixed
     */
    private function generateResult($result)
    {
        foreach (Post::get('types') as $key => $v) {
            if (isset(Post::get('alert')[$key]) && isset(Post::get('mail')[$key])) {
                $result[$key] = 3;
            } elseif (isset(Post::get('alert')[$key]) && !isset(Post::get('mail')[$key])) {
                $result[$key] = 1;
            } elseif (!isset(Post::get('alert')[$key]) && isset(Post::get('mail')[$key])) {
                $result[$key] = 2;
            } else {
                $result[$key] = 0;
            }
        }

        return $result;
    }
}
