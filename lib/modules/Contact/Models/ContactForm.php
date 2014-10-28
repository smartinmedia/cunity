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

namespace Cunity\Contact\Models;

use Cunity\Contact\Models\Db\Table\Contact;
use Cunity\Contact\View\ContactMail;
use Cunity\Core\Helper\UserHelper;
use Cunity\Core\View\Message;
use Cunity\Register\Models\Login;

/**
 * Class ContactForm
 * @package Cunity\Contact\Models
 */
class ContactForm
{
    /**
     *
     */
    public function __construct()
    {
        $this->handleInput();
    }

    /**
     *
     */
    private function handleInput()
    {
        if (isset($_POST['message'])) {
            $contactDb = new Contact();
            if ((Login::loggedIn())) {
                $res = $contactDb->insert([
                    "userid" => UserHelper::$USER->userid,
                    "firstname" => $_POST['firstname'],
                    "lastname" => $_POST['lastname'],
                    "email" => $_POST['email'],
                    "subject" => $_POST['subject'],
                    "message" => $_POST['message'],
                ]);
            } else {
                $res = $contactDb->insert([
                    "userid" => 0,
                    "firstname" => $_POST['firstname'],
                    "lastname" => $_POST['lastname'],
                    "email" => $_POST['email'],
                    "subject" => $_POST['subject'],
                    "message" => $_POST['message'],
                ]);
            }
            if ($res) {
                $cc = (isset($_POST['send_copy']) && $_POST['send_copy'] == 1) ? ["email" => $_POST['email'], "name" => $_POST['firstname'] . " " . $_POST['lastname']] : [];
                new ContactMail([], ["subject" => $_POST['subject'], "message" => $_POST['message']], $cc);
                new Message("Finished!", "Your Message was sent successfully!", "success");
            } else {
                new Message("Sorry!", "There was an error in our system, please try again later", "danger");
            }
        }
    }
}
