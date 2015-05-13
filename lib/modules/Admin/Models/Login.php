<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2015 Smart In Media GmbH & Co. KG                            ##
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

use Cunity\Core\Models\Generator\Url;
use Cunity\Core\Request\Post;
use Cunity\Core\Request\Session;

/**
 * Class Login.
 */
class Login
{
    /**
     * @param string $action
     */
    public function __construct($action = '')
    {
        if (method_exists($this, $action)) {
            call_user_func([$this, $action]);
        }
    }

    /**
     *
     */
    public static function loginRequired()
    {
        if (!self::loggedIn()) {
            header('Location:'.Url::convertUrl('index.php?m=admin&action=login'));
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
        return (Session::get('admin_loggedIn') === true && Session::get('user') !== null);
    }

    /**
     *
     */
    public function login()
    {
        if (Post::get('email') !== null) {
            if (Session::get('user')->email == Post::get('email') && Session::get('user')->password == sha1(Post::get('password').Session::get('user')->salt)) {
                Session::set('admin_loggedIn', true);
                header('Location:'.Url::convertUrl('index.php?m=admin'));
                exit();
            } else {
                $view = new \Cunity\Admin\View\Login();
                $view->assign('message', 'The entered data is not correct!');
                $view->show();
            }
        } else {
            $view = new \Cunity\Admin\View\Login();
            $view->assign('message', '');
            $view->show();
        }
    }
}
