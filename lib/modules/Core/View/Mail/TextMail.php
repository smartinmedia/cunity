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

namespace Cunity\Core\View\Mail;

/**
 * Class TextMail
 * @package Cunity\Core\View\Mail
 */
class TextMail extends MailView
{

    /**
     * @var string
     */
    protected $_templateDir = "core";
    /**
     * @var string
     */
    protected $_templateFile = "textmail.tpl";

    /**
     * @param $receiver
     * @param array $text
     */
    public function __construct($receiver, array $text)
    {
        parent::__construct();
        if ((!isset($receiver['name']) ||
                !isset($receiver['email'])) &&
            isset($receiver['userid'])) {
            $user = $_SESSION['user']->getTable()->search(
                "userid",
                $receiver['userid']
            );
            if ($user !== NULL) {
                $receiver['name'] = $user->name;
                $receiver['email'] = $user->email;
            }
        }

        $this->_receiver = $receiver;
        $this->_subject = $this->translate(
            $text['subject']['text'], $text['subject']['replaces']
        );
        $this->assign("name", $receiver["name"]);
        if (isset($text['content']))
            $this->assign(
                "content", $this->translate(
                    $text['content']['text'],
                    $text['content']['replaces']
                )
            );
        else
            $this->assign(
                "content", $this->translate(
                    $text['subject']['text'],
                    $text['subject']['replaces']
                )
            );
        $this->show();
    }
}
