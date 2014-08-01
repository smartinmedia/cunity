<?php

namespace Notifications\View;

use Core\View\Mail\MailView;

/**
 * Class NotificationMail
 * @package Notifications\View
 */
class NotificationMail extends MailView
{

    /**
     * @var string
     */
    protected $_templateDir = "notifications";
    /**
     * @var string
     */
    protected $_templateFile = "notification-mail.tpl";

    /**
     * @param array $receiver
     * @param array $data
     */
    public function __construct(array $receiver, array $data)
    {
        parent::__construct();
        $this->_receiver = $receiver;
        $this->_subject = $data['message'];
        $this->assign("message", $data['message']);
        $this->show();
    }

}
