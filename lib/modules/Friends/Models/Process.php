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

namespace Cunity\Friends\Models;

use Cunity\Core\Exception;
use Cunity\Core\View\Ajax\View;
use Cunity\Friends\Models\Db\Table\Relationships;

/**
 * Class Process
 * @package Cunity\Friends\Models
 */
class Process
{
    /**
     * @param $action
     */
    public function __construct($action)
    {
        if (method_exists($this, $action)) {
            call_user_func([$this, $action]);
        }
    }

    /**
     *
     */
    private function add()
    {
        RelationShipHelper::add(true);
    }

    /**
     *
     */
    private function block()
    {
        RelationShipHelper::block();
    }

    /**
     *
     */
    private function confirm()
    {
        RelationShipHelper::confirm();
    }

    /**
     *
     */
    private function remove()
    {
        RelationShipHelper::remove();
    }

    /**
     *
     */
    private function change()
    {
        RelationShipHelper::change();
    }

    /**
     * @throws Exception
     */
    private function loadData()
    {
        RelationShipHelper::loadData();
    }

    /**
     *
     */
    private function load()
    {
        $relations = new Relationships();
        $rows = $relations->getFullFriendList(">1", $_POST['userid']);
        $view = new View(true);
        $view->addData(["result" => $rows]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadOnline()
    {
        $view = new View(false);
        if ($_SESSION['user']->chat_available == 1) {
            $relations = new Relationships();
            $friends = $relations->loadOnlineFriends($_SESSION['user']->userid);
            $view->addData(["result" => $friends]);
            $view->setStatus(true);
        } else {
            $view->setStatus(true);
            $view->addData(["result" => [], "msg" => "disabled"]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function chatStatus()
    {
        $view = new View(false);
        if ($_POST['status'] == 1 || $_POST['status'] == 0) {
            $_SESSION['user']->chat_available = $_POST['status'];
            /** @noinspection PhpUndefinedMethodInspection */
            $view->setStatus($_SESSION['user']->save() > 0);
        }
        $view->sendResponse();
    }
}
