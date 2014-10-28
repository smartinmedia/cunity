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
use Cunity\Core\Helper\UserHelper;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\View\Ajax\View;
use Cunity\Friends\Models\Db\Table\Relationships;
use Cunity\Notifications\Models\Notifier;

/**
 * Class RelationShipHelper
 * @package Cunity\Friends\Models
 */
class RelationShipHelper
{
    /**
     *
     */
    public static function change()
    {
        UserHelper::breakOnMissingUserId();
        $relations = new Relationships();
        $res = $relations->updateRelation($_POST['userid'], UserHelper::$USER->userid, ["status" => $_POST['status']]);
        if ($res) {
            $view = new View($res !== false);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    public static function remove()
    {
        UserHelper::breakOnMissingUserId();
        $relations = new Relationships();
        $res = $relations->deleteRelation(UserHelper::$USER->userid, $_POST['userid']);
        if ($res) {
            $view = new View($res !== false);
            $view->sendResponse();
        }
    }

    /**
     * @param bool $notify
     */
    public static function confirm($notify = false)
    {
        UserHelper::breakOnMissingUserId();
        $relations = new Relationships();
        $res = $relations->updateRelation(UserHelper::$USER->userid, $_POST['userid'], ["status" => 2]);
        if ($res) {
            if ($notify) {
                Notifier::notify($_POST['userid'], UserHelper::$USER->userid, "confirmfriend", "index.php?m=profile&action=" . UserHelper::$USER->username);
            }

            $view = new View($res !== false);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    public static function block()
    {
        UserHelper::breakOnMissingUserId();
        $relations = new Relationships();
        $res = $relations->updateRelation(UserHelper::$USER->userid, $_POST['userid'], ["status" => 0, "sender" => UserHelper::$USER->userid, "receiver" => $_POST['userid']]);
        if ($res) {
            $view = new View($res !== false);
            $view->sendResponse();
        }
    }

    /**
     * @param bool $notify
     */
    public static function add($notify = false)
    {
        UserHelper::breakOnMissingUserId();
        $relations = new Relationships();
        $res = $relations->insert(["sender" => UserHelper::$USER->userid, "receiver" => $_POST['userid'], "status" => 1]);
        if ($res) {
            if ($notify) {
                Notifier::notify($_POST['userid'], UserHelper::$USER->userid, "addfriend", "index.php?m=profile&action=" . UserHelper::$USER->username);
            }

            $view = new View($res !== false);
            $view->sendResponse();
        }
    }

    /**
     * @throws Exception
     */
    public static function loadData()
    {
        $userid = $_POST['userid'];
        /** @noinspection PhpUndefinedMethodInspection */
        $users = UserHelper::$USER->getTable();
        /** @var Users $users */
        $result = $users->get($userid);
        if ($result === null) {
            throw new Exception("No User found with the given ID!");
        } else {
            $view = new View(true);
            $view->addData(["user" => $result->toArray(["pimg", "username", "firstname", "lastname"])]);
            $view->sendResponse();
        }
    }
}
