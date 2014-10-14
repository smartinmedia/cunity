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
 * Class Version 1231231231
 * @package Admin\Models\Updater\DatabaseUpdates
 */
class Version_1413270764 extends DbUpdateVersion implements DbCommandInterface
{

    protected $_timestamp = 1413270764;

    /**
     *
     */
    public function execute()
    {
        $this->_db->query("DROP TABLE IF EXISTS " . $this->_db->get_dbprefix() . "profilefields;");
        $this->_db->query("CREATE TABLE IF NOT EXISTS " . $this->_db->get_dbprefix() . "profilefields (
    id int(11) NOT NULL AUTO_INCREMENT,
  value varchar(255) NOT NULL,
  type_id int(11) DEFAULT NULL,
  registration tinyint(1) NOT NULL,
  required tinyint(1) NOT NULL,
  deleteable tinyint(1) NOT NULL,
  sorting int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;");

        $this->_db->query("INSERT INTO " . $this->_db->get_dbprefix() . "profilefields (id, value, type_id, registration, required, deleteable, sorting) VALUES
    (1, 'Sex', 1, 1, 1, 1, 3),
(2, 'Firstname', 4, 0, 1, 1, 1),
(3, 'Lastname', 4, 0, 1, 1, 2),
(4, 'Birthdate', 6, 0, 1, 1, 4);");

        $this->_db->query("DROP TABLE IF EXISTS " . $this->_db->get_dbprefix() . "profilefields_types;
CREATE TABLE IF NOT EXISTS " . $this->_db->get_dbprefix() . "profilefields_types (
    identifier int(11) NOT NULL AUTO_INCREMENT,
  value varchar(255) DEFAULT NULL,
  PRIMARY KEY (identifier)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;");

        $this->_db->query("INSERT INTO " . $this->_db->get_dbprefix() . "profilefields_types (identifier, value) VALUES
    (1, 'select'),
(2, 'radio'),
(3, 'text'),
(4, 'string'),
(5, 'email'),
(6, 'date');");

        $this->_db->query("DROP TABLE IF EXISTS " . $this->_db->get_dbprefix() . "profilefields_users;
CREATE TABLE IF NOT EXISTS " . $this->_db->get_dbprefix() . "profilefields_users (
    id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  profilefield_id int(11) NOT NULL,
  value text,
  PRIMARY KEY (id)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;");

        $this->_db->query("DROP TABLE IF EXISTS " . $this->_db->get_dbprefix() . "profilefields_values;
CREATE TABLE IF NOT EXISTS " . $this->_db->get_dbprefix() . "profilefields_values (
    identifier int(11) NOT NULL AUTO_INCREMENT,
  value text,
  profilefield_id int(11) DEFAULT NULL,
  sorting int(11) NOT NULL,
  PRIMARY KEY (identifier)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;");

        $this->_db->query("INSERT INTO " . $this->_db->get_dbprefix() . "profilefields_values (identifier, value, profilefield_id, sorting) VALUES
    (1, 'Female', 1, 1),
(2, 'Male', 1, 2);");
    }
}
