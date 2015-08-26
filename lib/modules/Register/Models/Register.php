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

namespace Cunity\Register\Models;

use Cunity\Core\Cunity;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Models\Validation\Email;
use Cunity\Core\Models\Validation\Password;
use Cunity\Core\Models\Validation\Username;
use Cunity\Core\Request\Post;
use Cunity\Core\View\Message;
use Cunity\Core\View\View;
use Cunity\Register\View\Registration;
use Cunity\Register\View\ResetPassword;
use Zend_Validate_Date;

/**
 * Class Register.
 */
class Register
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     *
     */
    public function reset()
    {
        $error_messages = [];
        $view = new ResetPassword();
        if (Post::get() !== null) {
            $users = new Users();
            $user = $users->search('email', Post::get('email'));
            if ($user !== null) {
                $tokendata = json_decode($user->password_token, true);
                if (Post::get('token') == $tokendata['token']) {
                    if (time() - $tokendata['time'] > 86400) {
                        $this->errors['token'] = 'The given token has expired! Every token is only valid for 24 hours';
                    } else {
                        $validatePassword = new Password();
                        if (!$validatePassword->passwordValid(Post::get('password'), Post::get('password_repeat'))) {
                            $this->errors['password'] = implode(',', $validatePassword->getMessages());
                            $this->errors['password_repeat'] = '';
                        } else {
                            $user->password = sha1(Post::get('password').$user->salt);
                            $user->password_token = null;
                            $user->save();
                            new Message('Done!', 'Your password was changed successfully! You can now login!', 'success');
                            exit();
                        }
                    }
                } else {
                    $this->errors['token'] = 'The given token is not correct!';
                }
            } else {
                $this->errors['email'] = 'Email was not found in our system!';
            }

            $this->assignErrors($view, $error_messages);
            $view->show();
        } else {
            $view->show();
        }
    }

    /**
     *
     */
    public function renderErrors()
    {
        $view = new Registration();
        $this->assignErrors($view, $this->errors);
        $view->render();
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function validateForm()
    {
        $validateMail = new Email();
        $validateUsername = new Username();
        $validatePassword = new Password();

        if (Cunity::get('settings')->getSetting('register.min_age')) {
            $validateBirthday = new Zend_Validate_Date(['format' => 'mm/dd/yyyy']);
            if (!$validateBirthday->isValid($_POST['birthday'])) {
                $this->errors['birthday'] = implode(',', $validateBirthday->getMessages());
            }
        }
        if (!$validateUsername->isValid(Post::get('username'))) {
            $this->errors['username'] = implode(',', $validateUsername->getMessages());
        }
        if (!$validateMail->isValid($_POST['email'])) {
            $this->errors['email'] = implode(',', $validateMail->getMessages());
        }
        if (!$validatePassword->passwordValid(Post::get('password'), Post::get('password_repeat'))) {
            $this->errors['password'] = implode(',', $validatePassword->getMessages());
            $this->errors['password_repeat'] = '';
        }

        return empty($this->errors);
    }

    /**
     * @param View $view
     * @param $error_messages
     */
    private function assignErrors(View $view, $error_messages)
    {
        if (!empty($this->errors)) {
            foreach ($this->errors as $error => $message) {
                if (!empty($message)) {
                    $error_messages[$error] = $view->translate($message);
                }
            }
            $view->assign('error_messages', $error_messages);
            $view->assign('success', false);
            $view->assign('values', Post::get());
        }
    }
}
