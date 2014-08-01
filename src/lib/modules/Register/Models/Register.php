<?php

namespace Register\Models;

use Core\Cunity;
use Core\Models\Db\Table\Users;
use Core\Models\Validation\Email;
use Core\Models\Validation\Password;
use Core\Models\Validation\Username;
use Core\View\Message;
use Register\View\Registration;
use Register\View\ResetPassword;
use Zend_Validate_Date;

/**
 * Class Register
 * @package Register\Models
 */
class Register {

    /**
     * @var array
     */
    private $errors = [];

    /**
     *
     */
    public function reset() {
        $view = new ResetPassword();
        if (!empty($_POST)) {
            $users = new Users();
            $user = $users->search("email", $_POST['email']);
            if ($user !== NULL) {
                $tokendata = json_decode($user->password_token, true);
                if ($_POST['token'] == $tokendata['token']) {
                    if (time() - $tokendata["time"] > 1800) {
                        $this->errors["token"] = "The given token has expired! Every token is only valid for 30 minutes";
                    } else {
                        $validatePassword = new Password();
                        if (!$validatePassword->passwordValid($_POST['password'], $_POST['password_repeat'])) {
                            $this->errors["password"] = implode(',', $validatePassword->getMessages());
                            $this->errors["password_repeat"] = "";
                        } else {
                            $user->password = sha1($_POST['password'] . $user->salt);
                            $user->password_token = NULL;
                            $user->save();
                            new Message("Done!", "Your password was changed successfully! You can now login!", "success");
                            exit();
                        }
                    }
                } else
                    $this->errors["token"] = "The given token is not correct!";
            } else
                $this->errors["email"] = "Email was not found in our system!";
            if (!empty($this->errors)) {
                foreach ($this->errors AS $error => $message)
                    if (!empty($message))
                        $error_messages[$error] = $view->translate($message);

                $view->assign("error_messages", $error_messages);
                $view->assign('success', false);
                $view->assign("values", $_POST);
            }
            $view->show();
        } else
            $view->show();
    }

    /**
     *
     */
    public function renderErrors() {
        $view = new Registration();
        $error_messages = [];
        if (!empty($this->errors)) {
            foreach ($this->errors AS $error => $message)
                if (!empty($message))
                    $error_messages[$error] = $view->translate($message);

            $view->assign("error_messages", $error_messages);
            $view->assign('success', false);
            $view->assign("values", $_POST);
        }
        $view->render();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function validateForm() {
        $validateMail = new Email();
        $validateUsername = new Username();
        $validatePassword = new Password();

        if (Cunity::get("settings")->getSetting("register.min_age")) {
            $validateBirthday = new Zend_Validate_Date(["format" => "mm/dd/yyyy"]);
            if (!$validateBirthday->isValid($_POST['birthday']))
                $this->errors['birthday'] = implode(',', $validateBirthday->getMessages());
        }
        if (!$validateUsername->isValid($_POST['username']))
            $this->errors["username"] = implode(',', $validateUsername->getMessages());
        if (!$validateMail->isValid($_POST['email']))
            $this->errors["email"] = implode(',', $validateMail->getMessages());
        if (!$validatePassword->passwordValid($_POST['password'], $_POST['password_repeat'])) {
            $this->errors["password"] = implode(',', $validatePassword->getMessages());
            $this->errors["password_repeat"] = "";
        }
        if (!isset($_POST['sex']) || ($_POST['sex'] != 'm' && $_POST['sex'] != "f"))
            $this->errors["sex"] = "Please select a gender";
        return empty($this->errors);
    }

}
