<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################
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

namespace Cunity\Core\Models\Mail;

use Cunity\Core\Cunity;
use Zend_Mail_Transport_Sendmail;
use Zend_Mail_Transport_Smtp;

/**
 * Class Mail
 * @package Cunity\Core\Models\Mail
 */
class Mail extends \Zend_Mail
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
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
    public function sendMail($body, $subject, array $receiver, array $cc = [])
    {
        $this->setBodyHtml($body);
        $this->addTo($receiver['email'], $receiver['name']);
        if (!empty($cc)) {
            $this->addCc($cc['email'], $cc['name']);
        }
        $this->setSubject($subject);
        $this->send();
    }
}
