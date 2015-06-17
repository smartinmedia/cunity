<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2015 Smart In Media GmbH & Co. KG                            ##
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
 * Class Version1434525113
 */
class Version1434525113 extends DbUpdateVersion implements DbCommandInterface
{
    /**
     *
     */
    public function execute()
    {
        $this->_db->query('CREATE TABLE IF NOT EXISTS '.$this->_db->getDbprefix().'filesharing_files (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  title varchar(255) NOT NULL,
  description text NOT NULL,
  filename varchar(255) NOT NULL,
  filenameondisc varchar(255) NOT NULL,
  PRIMARY KEY (id)
)');

        $this->_db->query('CREATE TABLE IF NOT EXISTS '.$this->_db->getDbprefix().'filesharing_rights (
  id int(11) NOT NULL AUTO_INCREMENT,
  file_id int(11) NOT NULL,
  user_id int(11) NOT NULL,
  all_friends tinyint(1) DEFAULT NULL,
  PRIMARY KEY (id)
)');
    }
}
