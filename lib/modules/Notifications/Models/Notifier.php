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

namespace Cunity\Notifications\Models;

use Cunity\Core\Request\Session;
use Cunity\Notifications\View\NotificationMail;

/**
 * Class Notifier.
 */
class Notifier
{
    /**
     * @var Notifier
     */
    private static $instance = null;
    /**
     * @var Db\Table\Notifications|null
     */
    private $db = null;
    /**
     * @var Db\Table\NotificationSettings|null
     */
    private $settings = null;
    /**
     * @var mixed|null
     */
    private $types = null;

    /**
     *
     */
    public function __construct()
    {
        $this->db = new Db\Table\Notifications();
        $this->settings = new Db\Table\NotificationSettings();
        $data = new \Zend_Config_Xml('modules/Notifications/lang/types.xml');
        $this->types = $data->types;
    }

    /**
     * @param $receiver
     * @param $sender
     * @param $type
     * @param $target
     * @param array $ways
     */
    public static function notify($receiver, $sender, $type, $target, $ways = ['alert', 'mail'])
    {
        if (is_array($receiver)) {
            foreach ($receiver as $user) {
                self::notify($user['userid'], $sender, $type, $target, $ways);
            }
        } else {
            /** @var Notifier $obj */
            $obj = self::getInstance();
            $st = $obj->settings->getSetting($type, $receiver);
            if (($st == 1 || $st == 3) && in_array('alert', $ways)) {
                $obj->db->insertNotification([
                    'userid' => $receiver,
                    'ref_userid' => $sender,
                    'type' => $type,
                    'target' => $target,
                ]);
            }
            if (($st == 2 || $st == 3) && in_array('mail', $ways)) {
                $receiverData = Session::get('user')->getTable()->get($receiver);
                $online = new \DateTime($receiverData['lastAction']);
                $now = new \DateTime();
                $diff = $now->diff($online, true);
                if ($diff->i > 3) {
                    $notificationData = self::getNotificationData($type);
                    new NotificationMail(['email' => $receiverData->email, 'name' => $receiverData->name], ['message' => \sprintf($notificationData, Session::get('user')->name), 'target' => $target]);
                }
            }
        }
    }

    /**
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $type
     *
     * @return mixed
     */
    public static function getNotificationData($type)
    {
        $obj = self::getInstance();
        $temp = $obj->types->toArray();

        return $temp[$type];
    }
}
