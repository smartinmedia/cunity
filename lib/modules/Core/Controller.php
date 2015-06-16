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

namespace Cunity\Core;

use Cunity\Core\Exceptions\ModuleNotActive;
use Cunity\Core\Exceptions\ModuleNotFound;
use Cunity\Core\Request\Get;
use Cunity\Core\Request\Request;
use Cunity\Core\View\Exception\View;
use Cunity\Register\Models\Login;

/**
 * Class Controller.
 */
class Controller
{
    /**
     * @throws \Exception
     */
    public function __construct()
    {
        View::initTranslator();
        array_walk_recursive($_GET, [$this, 'trimhtml']);
        array_walk_recursive($_POST, [$this, 'trimhtml']);

        Cunity::init();

        //use the filesdir hash as unique session name
        session_name(
            'cunity-'
            .Cunity::get('settings')->getSetting('core.filesdir')
        );
        session_start();
        if (Request::isAjaxRequest()) {
            set_exception_handler([$this, 'handleAjaxException']);
        } else {
            set_exception_handler([$this, 'handleException']);
        }
        $this->handleQuery();
    }

    /**
     *
     */
    protected function handleQuery()
    {
        if (Get::get('m') === null || Get::get('m') == '') {
            if (Login::loggedIn()) {
                header(
                    'Location:'
                    .Models\Generator\Url::convertUrl(
                        'index.php?m=profile'
                    )
                );
                exit();
            } else {
                Get::set('m', 'start');
            }
        }

        $moduleController = new Module(Get::get('m'));
        if (!Request::isAjaxRequest() && !$moduleController->isActive()) {
            throw new ModuleNotActive();
        } elseif ($moduleController->isValid()) {
            $classname = $moduleController->getClassName();
            new $classname();
        } else {
            throw new ModuleNotFound();
        }
    }

    /**
     * @param $e
     */
    public static function handleException($e)
    {
        new View($e);
    }

    /**
     * @param \Exception $exception
     */
    public static function handleAjaxException(\Exception $exception)
    {
        $view = new \Cunity\Core\View\Ajax\View();
        $view->setStatus(false);
        $view->addData(['msg' => $exception->getMessage()]);
        $view->sendResponse();
    }

    /**
     * @param $value
     */
    private function trimhtml(&$value)
    {
        $value = trim(htmlspecialchars($value, ENT_QUOTES));
    }
}
