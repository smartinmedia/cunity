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

namespace Cunity\Core\Models\Validation;

use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Request\Session;
use Cunity\Register\Models\Login;

/**
 * Class Username.
 */
class Username extends \Zend_Validate_Alnum
{
    /**
     *
     */
    const USED = 'used';
    /**
     *
     */
    const LENGTH = 'length';
    /**
     *
     */
    const INVALID = 'invalid';

    /**
     * @var array
     */
    protected $_messageTemplates = [
        self::USED => 'This username is already in use',
        self::LENGTH => 'The username-length should be between 2 and 20 characters!',
        self::INVALID => 'The username contains not allowed characters!',
    ];

    /**
     * @param string $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        if (empty($value) || strlen($value) < 2 || strlen($value) > 20) {
            $this->_error(self::LENGTH);

            return false;
        }
        $users = new Users();
        $user = $users->search('username', $value);
        if ($user !== null &&
            (Login::loggedIn() && ($user->userid !== Session::get('user')->userid))
        ) {
            $this->_error(self::USED);

            return false;
        }
        $status = preg_match('/^[A-Za-z0-9_.-]*$/', $value);
        if (false === $status || !$status) {
            $this->_error(self::INVALID);

            return false;
        }

        return true;
    }
}
