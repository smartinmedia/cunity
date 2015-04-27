<?php

namespace Cunity\Admin\Helper;

use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Models\Mail\Mail;

/**
 * Class NewsletterHelper
 */
class NewsletterHelper
{
    public static function sendMails($subject, $message, $isTest = false)
    {
        $users = new Users();

        if (!$isTest) {
            $users = $users->fetchAll('groupid != 0 AND groupid != 4')->toArray();
        } else {
            $users = [$users->fetchRow('userid = ' . $_SESSION['user']->userid)->toArray()];
        }

        $mailer = new Mail();
        $mailer->sendMail(
            $message,
            $subject,
            $users);
    }
}
