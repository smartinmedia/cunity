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

namespace Cunity\Profile\Models;

use Cunity\Core\Access\UserAccess;
use Cunity\Core\Exceptions\UnknownUser;
use Cunity\Core\Models\Db\Row\User;
use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Models\Request;
use Cunity\Core\Request\Get;
use Cunity\Core\Request\Post;
use Cunity\Core\View\Ajax\View;

/**
 * Class Profile.
 */
class Profile
{
    /**
     * @var array
     */
    protected $profileData = [];

    /**
     *
     */
    public function __construct()
    {
        if (Request::isAjaxRequest()) {
            $this->handleAjaxAction();
        }
        $this->checkUser();
        $this->render();
    }

    /**
     *
     */
    private function handleAjaxAction()
    {
        switch (Get::get('action')) {
            case 'getpins':
                $pins = new Db\Table\ProfilePins();
                $result = $pins->getAllByUser(Post::get('userid'));
                $view = new View(true);
                if (!is_array($result)) {
                    $result = $result->toArray();
                    foreach ($result as $i => $res) {
                        $result[$i]['content'] = htmlspecialchars_decode($res['content']);
                    }
                } else {
                    $result = [];
                }
                $view->addData(['result' => $result]);
                $view->sendResponse();
                break;
        }
    }

    /**
     *
     */
    private function checkUser()
    {
        /** @var Users $users */
        $users = $_SESSION['user']->getTable();
        if (Get::get('action') !== null && Get::get('action') !== '') {
            $result = $users->get(Get::get('action'), 'username');
            if (!$result instanceof User || $result['name'] === null) {
                throw new UnknownUser();
            }
        } else {
            $result = $users->get($_SESSION['user']->userid);
        }
        // Get a new user Object with all image-data
        $result = $result->toArray();
        $this->profileData = $result;
        if (isset($this->profileData['status']) && $this->profileData['status'] === 0 && $this->profileData['receiver'] == $_SESSION['user']->userid) {
            throw new UnknownUser();
        }
    }

    /**
     *
     */
    protected function render()
    {
        $users = $_SESSION['user']->getTable();
        $user = $users->get(Get::get('action'), 'username');
        UserAccess::profilePublic($user);
        $view = new \Cunity\Profile\View\Profile();
        $view->assign('profile', $this->profileData);
        $view->setMetaData(['title' => $this->profileData['name']]);
        $view->render();
    }
}
