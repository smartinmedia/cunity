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

namespace Cunity\Register\Models;

use Cunity\Core\Cunity;
use Cunity\Core\Exceptions\Exception;
use Cunity\Core\Exceptions\MissingParameter;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\Request\Post;
use Cunity\Core\Request\Session;
use Cunity\Core\View\Ajax\View;
use Cunity\Core\View\Message;
use Cunity\Register\View\ForgetPw;
use Cunity\Register\View\ForgetPwMail;
use Cunity\Register\View\Registration;

/**
 * Class Process.
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
     *
     */
    private function sendRegistration()
    {
        $register = new Register();
        if (!$register->validateForm()) {
            $register->renderErrors();
        } else {
            $users = new Users();
            if ($users->registerNewUser(Post::get())) {
                $view = new Registration();
                $view->assign('success', true);
                $view->render();
            }
        }
    }

    /**
     *
     */
    private function validate()
    {
        $users = new Users();
        $res = $users->search(Post::get('field'), Post::get('val'));
        $view = new View(true);
        $view->addData(['valid' => ($res === null)]);
        $view->sendResponse();
    }

    /**
     * @throws Exception
     */
    private function login()
    {
        if (Post::get('email') === null || Post::get('password') === null) {
            throw new MissingParameter();
        }

        $email = trim(Post::get('email'));
        $password = trim(Post::get('password'));
        $users = new Users();
        $user = $users->search('email', $email);
        if ($user !== null) {
            if ($user->passwordMatch($password)) {
                if ($user->groupid == 0) {
                    new Message('Sorry', "Your account is not verified! Please check your verification mail! if you have not received a mail, enter your email at \"I forgot my password\" and we will send you a new mail!", 'danger');
                } elseif ($user->groupid == 4) {
                    new Message('Sorry', 'Your Account is blocked! Please contact the Administrator', 'danger');
                } else {
                    $user->setLogin(isset(Post::get('save-login')));
                    header('Location:'.Url::convertUrl('index.php?m=profile'));
                    exit();
                }
            } else {
                new Message('Sorry', 'The entered data is not correct!', 'danger');
            }
        } else {
            new Message('Sorry', 'The entered data is not correct!', 'danger');
        }
    }

    /**
     *
     */
    private function logout()
    {
        if (Login::loggedIn()) {
            Session::get('user')->logout();
        }

        header('Location:'.Url::convertUrl('index.php?m=start'));
        exit();
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    private function verify()
    {
        if (Get::get('x') === null || Get::get('x') === '') {
            throw new MissingParameter();
        }
        $users = new Users();
        $user = $users->search('salt', Get::get('x'));
        if ($user !== null) {
            $user->groupid = 1;
            $user->save();
            $config = Cunity::get('config');
            $functions = $config->registerFunctions->toArray();
            foreach ($functions['module'] as $module) {
                call_user_func(["\Cunity\\".ucfirst($module)."\Controller", 'onRegister'], $user);
            }
            new Message('Ready to go!', 'Your account was verified! You can now login!', 'success');
        } else {
            new Message('Sorry', 'We cannot verify your account! The given data was not found!', 'danger');
        }
    }

    /**
     *
     */
    private function forgetPw()
    {
        if (Post::get('resetPw') === null) {
            $view = new ForgetPw();
            $view->render();
            exit;
        } else {
            $users = new Users();
            $user = $users->search('email', Post::get('email'));
            if ($user !== null) {
                $token = rand(123123, 999999);
                $user->password_token = json_encode(['token' => $token, 'time' => time()]);
                $user->save();
                new ForgetPwMail(['name' => $user->username, 'email' => $user->email], $token);
                new Message('Done!', 'Please check your mails! We have sent you a token to reset your password!', 'success');
                exit();
            }
        }
        $view = new ForgetPw();
        $view->assign('error', true);
        $view->render();
    }

    /**
     * @throws \Exception
     */
    private function delete()
    {
        $config = Cunity::get('config');
        $functions = $config->registerFunctions->toArray();
        foreach ($functions['module'] as $module) {
            call_user_func(["Cunity\\".ucfirst($module)."\Controller", 'onUnregister'], Session::get('user'));
        }
    }

    /**
     *
     */
    private function reset()
    {
        if (Login::loggedIn()) {
            header('Location:'.Url::convertUrl('index.php?m=profile'));
            exit;
        }
        $register = new Register();
        $register->reset();
    }
}
