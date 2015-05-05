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

namespace Cunity\Admin\Models;

use Cunity\Admin\Helper\NewsletterHelper;
use Cunity\Admin\Helper\UpdateHelper;
use Cunity\Contact\Models\Db\Table\Contact;
use Cunity\Core\Cunity;
use Cunity\Core\Models\Db\Abstractables\Table;
use Cunity\Core\Models\Db\Table\Modules;
use Cunity\Core\Models\Db\Table\Newsletter;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\View\Ajax\View;
use Cunity\Profile\Models\Db\Table\ProfileFields;
use Cunity\Profile\Models\Db\Table\ProfileFieldsValues;

/**
 * Class Process.
 */
class Process
{
    /**
     * @var array
     */
    private $validForms = ['config', 'settings', 'mailtemplates', 'modules', 'users', 'profilefields', 'contact', 'headline', 'update', 'newsletter'];

    /**
     * @param $form
     * @param string $action
     */
    public function __construct($form, $action = 'save')
    {
        if (in_array($form, $this->validForms) &&
            method_exists($this, $action)
        ) {
            $this->$action($form);
        }
    }

    /**
     * @param $form
     *
     * @throws \Exception
     * @throws \Zend_Config_Exception
     */
    private function save($form)
    {
        $res = [];
        switch ($form) {
            case 'settings':
            case 'headline':
                foreach ($_POST as $key => $value) {
                    if (strpos($key, 'settings-') !== false) {
                        $setting = explode('-', $key);
                        /* @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
                        $settings = \Cunity\Core\Cunity::get('settings');
                        $res[] = $settings->setSetting(preg_replace('/_/', '.', $setting[1], 1), $value);
                    }
                }
                break;
            case 'config':
                $config = new \Zend_Config_Xml('../data/config.xml');
                $configWriter = new \Zend_Config_Writer_Xml(['config' => new \Zend_Config(self::arrayMergeRecursiveDistinct($config->toArray(), $_POST['config'])), 'filename' => '../data/config.xml']);
                $configWriter->write();
                break;
            case 'mailtemplates':
                $settings = Cunity::get('settings');
                $res[] = $settings->setSetting('core.mail_header', $_POST['mail_header']);
                $res[] = $settings->setSetting('core.mail_footer', $_POST['mail_footer']);
                break;
            case 'modules':
                Cunity::set('modules', new Modules());
                $modules = Cunity::get('modules');
                $modules->update(['status' => $_POST['status']], 'id = '.$_POST['id']);
                break;
            case 'update':
                UpdateHelper::update();
                break;
            case 'users':
                Cunity::set('users', new Users());
                /** @var Users $users */
                $users = Cunity::get('users');

                if (null !== $_REQUEST['userid']) {
                    if ('' !== $_REQUEST['groupid']) {
                        $users->update(['groupid' => $_REQUEST['groupid']], 'userid = '.$_REQUEST['userid']);
                    } else {
                        $users->delete('userid = '.$_REQUEST['userid']);
                    }
                } else {
                    $users->registerNewUser($_REQUEST);
                }
                break;
        }

        $this->sendResponse($res);
    }

    /**
     * @param array $array1
     * @param array $array2
     *
     * @return array
     *
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function arrayMergeRecursiveDistinct(array $array1, array $array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::arrayMergeRecursiveDistinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * @param $form
     */
    private function delete($form)
    {
        $primary = 'id';

        switch ($form) {
            case 'profilefields':
                $object = new ProfileFields();
                break;
            case 'modules':
                $object = new Modules();
                break;
            case 'contact':
                $primary = 'contact_id';
                $object = new Contact();
                break;
            default:
                break;
        }

        /* @var Table $object */
        $object->delete($primary.' = '.$_REQUEST['id']);
        $this->sendResponse();
    }

    /**
     * @param $res
     */
    protected function sendResponse($res = [])
    {
        $view = new View(!in_array(false, $res));
        $view->addData(['panel' => $_POST['panel']]);
        $view->sendResponse();
    }

    /**
     * @param $form
     */
    private function insert($form)
    {
        switch ($form) {
            case 'profilefields':
                $object = new ProfileFields();
                $newId = $object->insert($_REQUEST);
                $possibleValues = explode(',', $_REQUEST['possiblevalues']);
                $sorting = 1;

                foreach ($possibleValues as $_value) {
                    $value = new ProfileFieldsValues();
                    $value->insert(['value' => $_value, 'profilefield_id' => $newId]);
                    $sorting++;
                }

                break;
            case 'newsletter':
                NewsletterHelper::sendMails($_REQUEST['subject'], $_REQUEST['message'], ($_REQUEST['type'] === 'test'));

                if ($_REQUEST['type'] !== 'test') {
                    $object = new Newsletter();
                }
            default:
                if (is_object($object)) {
                    $object->insert($_REQUEST);
                }
                break;
        }

        /* @var Table $object */
        $this->sendResponse();
    }
}
