<?php

namespace Register\View;

use Core\View\Mail\MailView;

/**
 * Class ForgetPwMail
 * @package Register\View
 */
class ForgetPwMail extends MailView
{

    /**
     * @var string
     */
    protected $_templateDir = "register";
    /**
     * @var string
     */
    protected $_templateFile = "forgetpw-mail.tpl";

    /**
     * @var string
     */
    protected $_subject = "Your new password";

    /**
     * @param $receiver
     * @param $password
     */
    public function __construct($receiver, $password)
    {
        parent::__construct();
        $this->_receiver = $receiver;
        $this->assign("name", $receiver["name"]);
        $this->assign('password', $password);
        $this->show();
    }

}
