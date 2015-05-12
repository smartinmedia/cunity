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

namespace Cunity\Admin\Models\Pages;

use Cunity\Core\Models\Db\Table\Menu;
use Cunity\Core\Request\Post;
use Cunity\Core\View\Ajax\View;

/**
 * Class Appearance.
 */
class Appearance extends PageAbstract
{
    /**
     *
     */
    public function __construct()
    {
        if (Post::get() !== null && Post::get() !== '') {
            $this->handleRequest();
        } else {
            $this->loadData();
            $this->render('appearance');
        }
    }

    /**
     *
     */
    private function handleRequest()
    {
        $view = new View(true);
        switch (Post::get('action')) {
            case 'loadMenu':
                $menu = new Menu();
                $view->addData(['main' => $menu->getMainMenu(), 'footer' => $menu->getFooterMenu()]);
                $view->sendResponse();
                break;
            case 'addMenuItem':
                $menu = new Menu();
                $res = $menu->addMenuItem(Post::get());
                $view->addData(['data' => $res]);
                $view->sendResponse();
                break;
            case 'updateMenu':
                $mainMenu = explode(',', Post::get('main-menu'));
                $footerMenu = explode(',', Post::get('footer-menu'));
                $menu = new Menu();
                $res = [];
                if ($menu->deleteBut(array_merge($mainMenu, $footerMenu))) {
                    foreach ($mainMenu as $i => $m) {
                        $res[] = (false !== $menu->update(['pos' => $i], $menu->getAdapter()->quoteInto('id=?', $m)));
                    }
                    foreach ($footerMenu as $i => $m) {
                        $res[] = (false !== $menu->update(['pos' => $i], $menu->getAdapter()->quoteInto('id=?', $m)));
                    }
                }
                $view->addData(['panel' => 'menu-panel']);
                $view->setStatus(!in_array(false, $res));
                $view->sendResponse();
                break;
        }
    }

    /**
     * @throws \Exception
     */
    private function loadData()
    {
        $modules = new \Cunity\Core\Models\Db\Table\Modules();
        $installedModules = $modules->getModules()->toArray();
        $config = \Cunity\Core\Cunity::get('config');
        $this->assignments['smtp_check'] = $config->mail->smtp_check;
        $this->assignments['modules'] = $installedModules;
    }
}
