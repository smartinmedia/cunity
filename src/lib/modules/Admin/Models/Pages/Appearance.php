<?php

namespace Admin\Models\Pages;

use Core\Cunity;
use Core\Models\Db\Table\Modules;
use Core\Models\Db\Table\Menu;
use Core\View\Ajax\View;

/**
 * Class Appearance
 * @package Admin\Models\Pages
 */
class Appearance extends PageAbstract {

    /**
     *
     */
    public function __construct() {
        if (isset($_POST) && !empty($_POST)) {
            $this->handleRequest();
        } else {
            $this->loadData();
            $this->render("appearance");
        }
    }

    private function handleRequest() {
        $view = new View(true);
        switch ($_POST['action']) {
            case 'loadMenu':
                $menu = new Menu();
                $view->addData(["main" => $menu->getMainMenu(), "footer" => $menu->getFooterMenu()]);
                $view->sendResponse();
                break;
            case 'addMenuItem':
                $menu = new Menu();                
                $res = $menu->addMenuItem($_POST);
                $view->addData(["data" => $res]);
                $view->sendResponse();
                break;
            case 'updateMenu':
                $mainMenu = explode(',', $_POST['main-menu']);
                $footerMenu = explode(',', $_POST['footer-menu']);
                $menu = new Menu();
                $res = [];
                if ($menu->deleteBut(array_merge($mainMenu, $footerMenu))) {
                    foreach ($mainMenu AS $i => $m)
                        $res[] = (false !== $menu->update(["pos" => $i], $menu->getAdapter()->quoteInto("id=?", $m)));
                    foreach ($footerMenu AS $i => $m)
                        $res[] = (false !== $menu->update(["pos" => $i], $menu->getAdapter()->quoteInto("id=?", $m)));
                }
                $view->addData(["panel" => "menu-panel"]);
                $view->setStatus(!in_array(false, $res));
                $view->sendResponse();
                break;
        }
    }

    /**
     * @throws \Exception
     */
    private function loadData() {
        $modules = new Modules();
        $installedModules = $modules->getModules()->toArray();
        $config = Cunity::get("config");
        $this->assignments['smtp_check'] = $config->mail->smtp_check;
        $this->assignments['modules'] = $installedModules;
    }

}
