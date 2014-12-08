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

namespace Cunity\Admin\Helper;
use Cunity\Core\Cunity;

/**
 * Class UpdateHelper
 * @package Cunity\Admin\Helper
 */
class UpdateHelper
{
    /**
     * @var string
     */
//    public static $UPDATECHECKURL = 'http://server.cunity.net/version.php';
    public static $UPDATECHECKURL = 'http://10.135.0.52/intra/version.php';

    /**
     * @var string
     */
//    public static $LATESTURL = 'http://server.cunity.net/latest.zip';
    public static $LATESTURL = 'http://10.135.0.52/intra/latest.zip';

    /**
     * @return mixed
     */
    public static function hasUpdates()
    {
        $version = file_get_contents(self::$UPDATECHECKURL, 'r');
        return version_compare(self::getVersion(), $version, '<');
    }

    /**
     * @return mixed
     * @throws \Cunity\Core\Exception
     */
    public static function getVersion()
    {
        $config = Cunity::get("config");

        return $config->site->version;
    }

    /**
     *
     */
    public static function update()
    {

    }
}
