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

namespace Cunity\Register\Models;

use Cunity\Core\Models\Db\Row\User;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\Models\Request;
use Cunity\Core\View\Ajax\View;

/**
 * Class Login.
 */
class Login
{
    /**
     *
     */
    public static function loginRequired()
    {
        if (!self::loggedIn()) {
            $res = self::checkAutoLogin(false);
            if ($res !== false && $res instanceof User) {
                $res->setLogin(true);
                header('Location:'.Url::convertUrl('index.php?m=profile'));
            } elseif (!isset($_GET['m']) || $_GET['m'] != 'start') {
                if (!Request::isAjaxRequest()) {
                    header('Location:'.Url::convertUrl('index.php?m=start'));
                } else {
                    $view = new View(false);
                    $view->addData(['session' => 0]);
                    $view->sendResponse();
                }
            }
            exit();
        } else {
            return;
        }
    }

    /**
     * @return bool
     */
    public static function loggedIn()
    {
        return (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true && isset($_SESSION['user']) && $_SESSION['user'] instanceof User);
    }

    /**
     * @param bool $autologin
     *
     * @return bool|null|\Zend_Db_Table_Row_Abstract
     */
    public static function checkAutoLogin($autologin = true)
    {
        if (!isset($_COOKIE['cunity-login']) || !isset($_COOKIE['cunity-login-token'])) {
            return false;
        }
        $users = new Users();
        $user = $users->search('username', base64_decode($_COOKIE['cunity-login']));
        if (md5($user->salt.'-'.$user->registered.'-'.$user->userhash) == $_COOKIE['cunity-login-token']) {
            if ($autologin) {
                $user->setLogin(true);
                header('Location:'.Url::convertUrl('index.php?m=profile'));
                exit();
            } else {
                return $user;
            }
        }

        return false;
    }
}
