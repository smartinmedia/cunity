<?php

namespace Cunity\Admin\Helper;

use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Models\Mail\Mail;
use Cunity\Core\Request\Session;

/**
 * Class NewsletterHelper.
 */
class NewsletterHelper
{
    /**
     * @param $subject
     * @param $message
     * @param bool|false $isTest
     */
    public static function sendMails($subject, $message, $isTest = false)
    {
        $users = new Users();

        if (!$isTest) {
            $userList = $users->fetchAll('groupid != 0 AND groupid != 4')->toArray();
        } else {
            $userList = [$users->fetchRow('userid = '.Session::get('user')->userid)->toArray()];
        }

        $mailer = new Mail();

        foreach ($userList as $user)
        {
            set_time_limit(30);

            @$mailer->sendMail(
                nl2br(htmlentities($message)),
                utf8_decode($subject),
                $user);
        }
    }
}
