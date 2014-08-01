<?php

use Admin\Models\Updater\DbCommandInterface;
use Admin\Models\Updater\DbUpdateVersion;

/**
 * Class Version 1231231231
 * @package Admin\Models\Updater\DatabaseUpdates
 */
class Version_1406470410 extends DbUpdateVersion implements DbCommandInterface {

    protected $_timestamp = 1406470410;

    /**
     * 
     */
    public function execute() {
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.contact_mail' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'contact_mail';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.design' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'design';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.filesdir' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'filesdir';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.fullname' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'fullname';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.language' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'language';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.siteurl' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'siteurl';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.sitename' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'sitename';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.description' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'description';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.mail_header' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'mail_header';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'core.mail_footer' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'mail_footer';");
        
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'register.permissions' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'register_permissions';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'register.notification' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'register_notification';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'register.min_age' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'register_min_age';");
        $this->_db->query("UPDATE `".$this->_db->get_dbprefix()."settings` SET `name` = 'messages.chat' WHERE `".$this->_db->get_dbprefix()."settings`.`name` = 'chat';");
    }

}
