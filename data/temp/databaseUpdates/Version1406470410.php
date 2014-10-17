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
class Version1406470410 extends DbUpdateVersion implements DbCommandInterface {

    protected $_timestamp = 1406470410;

    /**
     * 
     */
    public function execute() {
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.contact_mail' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'contact_mail';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.design' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'design';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.filesdir' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'filesdir';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.fullname' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'fullname';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.language' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'language';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.siteurl' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'siteurl';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.sitename' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'sitename';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.description' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'description';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.mail_header' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'mail_header';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'core.mail_footer' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'mail_footer';");
        
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'register.permissions' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'register_permissions';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'register.notification' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'register_notification';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'register.min_age' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'register_min_age';");
        $this->_db->query("UPDATE `".$this->_db->getDbprefix()."settings` SET `name` = 'messages.chat' WHERE `".$this->_db->getDbprefix()."settings`.`name` = 'chat';");
    }

}
