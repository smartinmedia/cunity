<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
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

use Cunity\Core\Cunity;

/**
 * Class Birthday.
 */
class Birthday extends \Zend_Validate_Abstract
{
    /**
     *
     */
    const INVALID = 'invalid';
    /**
     *
     */
    const TOOYOUNG = 'tooyoung';

    /**
     * @var array
     */
    protected $_messageTemplates = [
        self::INVALID => 'Please enter a valid date!',
        self::TOOYOUNG => 'You are too young to register! Minimum age is ',
    ];

    /**
     * @throws \Cunity\Core\Exception
     */
    public function __construct()
    {
        $this->_messageTemplates[self::TOOYOUNG] .= Cunity::get('settings')->getSetting('register.min_age');
    }

    /**
     * @param mixed $value
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        if (!is_array($value) || empty($value['month']) || empty($value['day']) || empty($value['year'])) {
            $this->_error(self::INVALID);
        } elseif (!checkdate($value['month'], $value['day'], $value['year'])) {
            $this->_error(self::INVALID);
        } else {
            $now = new \DateTime();
            $received = new \DateTime($value['year'].'-'.$value['month'].'-'.$value['day']);
            if ($received->diff($now)->y < Cunity::get('settings')->getSetting('register.min_age')) {
                $this->_error(self::TOOYOUNG);
            } else {
                return true;
            }
        }

        return false;
    }
}
