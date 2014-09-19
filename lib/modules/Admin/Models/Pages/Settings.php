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


namespace Admin\Models\Pages;

use Comments\Models\Db\Table\Comments;
use Core\Cunity;
use Core\View\Ajax\View;
use Core\Models\Mail\Mail;
use Pages\Models\Db\Table\Pages;

/**
 * Class Settings
 * @package Admin\Models\Pages
 */
class Settings extends PageAbstract {

    /**
     *
     */
    public function __construct() {
        if (isset($_POST) && !empty($_POST)) {
            $this->handleRequest();
        } else {
            $this->loadData();
            $this->render("settings");
        }
    }

    /**
     * @throws \Exception
     */
    private function loadData() {
        $langIterator = new \DirectoryIterator("modules/Core/lang");
        $designIterator = new \DirectoryIterator("../style");
        foreach ($designIterator AS $design) {
            if ($design->isDir() && $design->isReadable() && !$design->isDot()) {
                $this->assignments['availableDesigns'][] = [$design->getBasename(), file_get_contents($design->getRealPath() . DIRECTORY_SEPARATOR . "name.txt")];
            }
        }

        foreach ($langIterator AS $lang)
            if ($lang->isReadable() && $lang->getExtension() == "php")
                $this->assignments['availableLanguages'][] = explode("-", $lang->getBasename(".php"));
        $this->assignments["config"] = Cunity::get("config");
    }

    /**
     *
     */
    private function handleRequest() {
        $view = new View();
        switch ($_POST['action']) {
            case "sendTestMail":
                $mail = new Mail();
                $res = $mail->sendMail("TestMail from cunity", "Cunity - Testmail", ["name" => "Cunity Admin", "email" => $_POST['mail']]);
                $view->setStatus($res !== NULL);
                break;
            case "loadPages":
                $pages = new Pages();
                $res = $pages->loadPages();
                $view->setStatus($res !== NULL);
                $view->addData(["pages" => $res->toArray()]);
                break;
            case "deletePage":
                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $pages = new Pages();
                    $status = $pages->deletePage($_POST['id']);
                    if ($status !== false && false) {
                        $comments = new Comments();
                        $status = $comments->removeAllComments($_POST['id'], "page");
                    } else
                        $status = true;
                    $view->setStatus($status);
                    $view->sendResponse();
                } else
                    $view->setStatus(false);
                break;
            case 'addPage':
                $pages = new Pages();
                $res = $pages->addPage($_POST);
                $page = $pages->getPage($res);
                $view->setStatus($res !== NULL && $res !== false);
                $page->content = html_entity_decode($page->content);
                $view->addData(["page" => $page->toArray()]);
                break;
        }
        $view->sendResponse();
    }

}
