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

namespace Cunity\Core\Exceptions;

use Psr\Log\LogLevel;
use Cunity\Core\Helper\LogHelper;

/**
 * Class Exception.
 */
abstract class Exception extends \Exception
{
    /**
     * @var int
     */
    protected $errorCode = 0;

    /**
     * @var string
     */
    protected $logLevel = LogLevel::INFO;

    /**
     * @var array
     */
    private static $errorCodes = [
        0 => 'Unknown error',
        1 => 'Error code does not exist',
        2 => 'Instance not found',
        3 => 'Unknown user',
        4 => 'Undefined setting',
        5 => 'Unknown file',
        6 => 'Missing parameter',
        7 => 'Unknown directory',
        8 => 'Not writeable',
        9 => 'Directory not writeable',
        10 => 'File not writeable',
        11 => 'Module not active',
        12 => 'Module not found',
        13 => 'Not allowed',
        14 => 'Event not found',
        15 => 'Category not found',
        16 => 'Forum not found',
        17 => 'Thread not found',
        18 => 'Album not found',
        404 => 'Page not found',
    ];

    /**
     *
     */
    public function __construct()
    {
        if (!array_key_exists($this->errorCode, self::$errorCodes)) {
            throw new ErrorNotFound();
        }

        $this->log();

        parent::__construct(self::$errorCodes[$this->errorCode], $this->errorCode);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__.": [{$this->errorCode}]: {".self::$errorCodes[$this->errorCode]."}\n";
    }

    /**
     *
     */
    protected function log()
    {
        switch ($this->logLevel) {
            case LogLevel::EMERGENCY:
            case LogLevel::ALERT:
            case LogLevel::CRITICAL:
            case LogLevel::ERROR:
                $log = new LogHelper();
                $log->log($this->logLevel, self::$errorCodes[$this->errorCode], $_SERVER);
                break;
            case LogLevel::WARNING:
            case LogLevel::NOTICE:
            case LogLevel::INFO:
            case LogLevel::DEBUG:
            default:
                break;
        }
    }
}
