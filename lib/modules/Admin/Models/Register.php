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
namespace Cunity\Admin\Models;

use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Models\Validation\Email;
use Cunity\Core\Models\Validation\Password;
use Cunity\Register\View\ForgetPw;
use Cunity\Register\View\Registration;
use Zend_Validate;
use Zend_Validate_Alpha;
use Zend_Validate_StringLength;

/**
 * Class Register.
 */
class Register
{
    /**
     * @var Users
     */
    protected $_users = null;
    /**
     * @var array
     */
    private $errors = [];

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
        $this->_users = new Users();
        if (!$this->validateForm()) {
            $this->renderErrors();
        } else {
            if ($this->_users->add($_POST)) {
                $view = new Registration();
                $view->assign('success', true);
                $view->render();
            }
        }
    }

    /**
     * @return bool
     */
    private function validateForm()
    {
        $validateAlpha = new Zend_Validate_Alpha();
        $validateMail = new Email();
        $validateUsername = new Zend_Validate();
        $validatePassword = new Password();

        $validateUsername->addValidator(new Zend_Validate_StringLength(['max' => 20, 'min' => 2]), true)->addValidator(new \Zend_Validate_Alnum());

        if (!$validateUsername->isValid($_POST['username'])) {
            $this->errors['username'] = 'Your username is invalid!';
        }
        if (!$validateMail->isValid($_POST['email'])) {
            $this->errors['email'] = implode(',', $validateMail->getMessages());
        }
        if (!$validatePassword->passwordValid($_POST['password'], $_POST['password-repeat'])) {
            $this->errors['password'] = implode(',', $validatePassword->getMessages());
            $this->errors['password_repeat'] = '';
        }
        if (!isset($_POST['sex']) || ($_POST['sex'] != 'm' && $_POST['sex'] != 'f')) {
            $this->errors['sex'] = 'Please select a gender';
        }
        if (!$validateAlpha->isValid($_POST['firstname'])) {
            $this->errors['firstname'] = 'The Firstname is invalid';
        }
        if (!$validateAlpha->isValid($_POST['lastname'])) {
            $this->errors['lastname'] = 'The Lastname is invalid';
        }

        return empty($this->errors);
    }

    /**
     *
     */
    private function renderErrors()
    {
        $view = new Registration();
        $error_messages = [];
        if (!empty($this->errors)) {
            foreach ($this->errors as $error => $message) {
                $view->assign('input_error_'.$error, 'error');
                $error_messages[] = $message;
            }
            $view->assign('error_messages', $error_messages);
        }
        $view->render();
    }

    /**
     *
     */
    private function forgetPw()
    {
        if (!isset($_POST['resetPw'])) {
            $view = new ForgetPw();
            $view->render();
        }
    }
}
