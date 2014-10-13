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

namespace Cunity\Admin\Models;

use Cunity\Core\Cunity;
use Cunity\Core\Models\Db\Table\Modules;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\View\Ajax\View;

/**
 * Class Process
 * @package Cunity\Admin\Models
 */
class Process
{
    /**
     * @var array
     */
    private $validForms = ['config', 'settings', 'mailtemplates', 'modules', 'users'];

    /**
     * @param $form
     */
    public function __construct($form)
    {
        if (in_array($form, $this->validForms)) {
            $this->save($form);
        }
    }

    /**
     * @param $form
     * @throws \Exception
     * @throws \Zend_Config_Exception
     */
    private function save($form)
    {

        $res = [];
        switch ($form) {
            case "settings":
                foreach ($_POST as $key => $value) {
                    if (strpos($key, "settings-") !== false) {
                        $setting = explode("-", $key);
                        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
                        $settings = \Cunity\Core\Cunity::get("settings");
                        $res[] = $settings->setSetting(preg_replace('/_/', '.', $setting[1], 1), $value);
                    }
                }
                break;
            case "config":
                $config = new \Zend_Config_Xml("../data/config.xml");
                $configWriter = new \Zend_Config_Writer_Xml(["config" => new \Zend_Config(self::array_merge_recursive_distinct($config->toArray(), $_POST['config'])), "filename" => "../data/config.xml"]);
                $configWriter->write();
                break;
            case "mailtemplates":
                $settings = Cunity::get("settings");
                $res[] = $settings->setSetting("core.mail_header", $_POST['mail_header']);
                $res[] = $settings->setSetting("core.mail_footer", $_POST['mail_footer']);
                break;
            case 'modules':
                Cunity::set('modules', new Modules());
                $modules = Cunity::get("modules");
                $modules->update(['status' => $_POST['status']], 'id = '.$_POST['id']);
                break;
            case 'users':
                Cunity::set('users', new Users());
                /** @var Users $users */
                $users = Cunity::get("users");

                if (null !== $_REQUEST['userid']) {
                    $users->update(['groupid' => $_REQUEST['groupid']], 'userid = '.$_REQUEST['userid']);
                } else {
                    $users->registerNewUser($_REQUEST);
                    exit;
                }
                break;
        }
        $view = new View(!in_array(false, $res));
        $view->addData(["panel" => $_POST['panel']]);
        $view->sendResponse();
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function array_merge_recursive_distinct(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }
}
