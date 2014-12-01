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

use Cunity\Admin\Models\Updater\DbCommandInterface;
use Cunity\Admin\Models\Updater\DbUpdateVersion;

/**
 * Class Version1417433051
 */
class Version1417433051 extends DbUpdateVersion implements DbCommandInterface
{
    /**
     *
     */
    public function execute()
    {
        $this->_db->query("ALTER TABLE  `".$this->_db->getDbprefix()."settings` CHANGE  `value`  `value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;");
        $this->_db->query("INSERT INTO `".$this->_db->getDbprefix()."settings` (`name`, `value`) VALUES ('core.headline',
                                                            '&lt;img src=&quot;data:image/gif;base64,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&quot; style=&quot;width: 135px;&quot;&gt;gbxcv&lt;span style=&quot;font-size: 36px;&quot;&gt;hsdfghd&lt;/span&gt;fh');");
    }
}
