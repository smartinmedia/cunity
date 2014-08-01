<?php

namespace Core\Models\Mail;

use Core\Cunity;
use Zend_Mail_Transport_Smtp;
use Zend_Mail_Transport_Sendmail;

/**
 * Class Mail
 * @package Core\Models\Mail
 */
class Mail extends \Zend_Mail {

    /**
     * @throws \Exception
     */
    public function __construct() {
        parent::__construct();
        $config = Cunity::get("config");
        $transport = ($config->mail->smtp == 1) ? new Zend_Mail_Transport_Smtp($config->mail->params->host, $config->mail->params->toArray()) : new \Zend_Mail_Transport_Sendmail();
        parent::setDefaultTransport($transport);
        parent::setDefaultFrom($config->mail->sendermail, $config->mail->sendername);
    }

    /**
     * @param $body
     * @param $subject
     * @param array $receiver
     * @param array $cc
     * @throws \Zend_Mail_Exception
     */
    public function sendMail($body, $subject, array $receiver, array $cc = []) {
        $this->setBodyHtml($body);
        $this->addTo($receiver['email'], $receiver['name']);
        if (!empty($cc))
            $this->addCc($cc['email'], $cc['name']);
        $this->setSubject($subject);
        $this->send();
    }

}
