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

/**
 * Class Password.
 */
class Password extends \Zend_Validate_StringLength
{
    /**
     *
     */
    const LENGTH = 'length';
    /**
     *
     */
    const EMPTYSTRING = 'empty';
    /**
     *
     */
    const MATCH = 'match';
    /**
     * @var int
     */
    protected $_min = 6;
    /**
     * @var int
     */
    protected $_max = 30;
    /**
     * @var array
     */
    protected $_messageTemplates = [
        self::EMPTYSTRING => 'Please enter a password',
        self::LENGTH => 'The Password-Length should be between 6 and 30 characters!',
        self::MATCH => 'Your entered passwords do not match',
    ];

    /**
     * @param $password
     * @param $passwordRepeat
     *
     * @return bool
     */
    public function passwordValid($password, $passwordRepeat)
    {
        if ($password == '') {
            $this->_error(self::EMPTYSTRING);

            return false;
        }
        if (strlen($password) < $this->_min || strlen($password) > $this->_max) {
            $this->_error(self::LENGTH);

            return false;
        } elseif ($password !== $passwordRepeat) {
            $this->_error(self::MATCH);

            return false;
        } else {
            return parent::isValid($password);
        }
    }
}
