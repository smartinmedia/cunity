<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2015 Smart In Media GmbH & Co. KG                            ##
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

namespace Cunity\Contact\Models;

use Cunity\Contact\Models\Db\Table\Contact;
use Cunity\Contact\View\ContactMail;
use Cunity\Core\Models\Db\Row\User;
use Cunity\Core\Request\Post;
use Cunity\Core\View\Message;
use Cunity\Register\Models\Login;

/**
 * Class ContactForm.
 */
class ContactForm
{
    /**
     * @var User
     */
    protected $user;

    /**
     *
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->handleInput();
    }

    /**
     *
     */
    private function handleInput()
    {
        if (Post::get('message') !== null) {
            $contactDb = new Contact();
            $res = $contactDb->insert([
                'userid' => (Login::loggedIn()) ? $this->user->userid : 0,
                'firstname' => Post::get('firstname'),
                'lastname' => Post::get('lastname'),
                'email' => Post::get('email'),
                'subject' => Post::get('subject'),
                'message' => Post::get('message'),
            ]);
            if ($res) {
                $cc = (Post::get('send_copy') == 1) ? ['email' => Post::get('email'), 'name' => Post::get('firstname').' '.Post::get('lastname')] : [];
                new ContactMail([], ['subject' => Post::get('subject'), 'message' => Post::get('message')], $cc);
                new Message('Finished!', 'Your Message was sent successfully!', 'success');
            } else {
                new Message('Sorry!', 'There was an error in our system, please try again later', 'danger');
            }
        }
    }
}
