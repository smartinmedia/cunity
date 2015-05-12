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

use Cunity\Comments\Models\Db\Table\Comments;
use Cunity\Core\Cunity;
use Cunity\Core\Models\Mail\Mail;
use Cunity\Core\Request\Post;
use Cunity\Core\View\Ajax\View;
use Cunity\Pages\Models\Db\Table\Pages;
use Cunity\Profile\Models\Db\Table\ProfileFields;

/**
 * Class Settings.
 */
class Settings extends PageAbstract
{
    /**
     * @var View
     */
    protected $view;

    /**
     *
     */
    public function __construct()
    {
        if (Post::get() !== null && Post::get() !== '') {
            $this->view = new View();
            $this->handleRequest();
        } else {
            $this->loadData();
            $this->render('settings');
        }
    }

    /**
     *
     */
    private function handleRequest()
    {
        switch (Post::get('action')) {
            case 'sendTestMail':
                $mail = new Mail();
                $mail->sendMail('TestMail from cunity', 'Cunity - Testmail', ['name' => 'Cunity Admin', 'email' => Post::get('mail')]);
                $this->view->setStatus(true);
                break;
            case 'loadPages':
                $pages = new Pages();
                $res = $pages->loadPages();
                $this->view->setStatus($res !== null);
                $this->view->addData(['pages' => $res->toArray()]);
                break;
            case 'deletePage':
                if (Post::get('id') !== null && Post::get('id') !== '') {
                    $pages = new Pages();
                    $status = $pages->deletePage(Post::get('id'));
                    if ($status !== false && false) {
                        $comments = new Comments();
                        $status = $comments->removeAllComments(Post::get('id'), 'page');
                    } else {
                        $status = true;
                    }
                    $this->view->setStatus($status);
                    $this->view->sendResponse();
                } else {
                    $this->view->setStatus(false);
                }
                break;
            case 'addPage':
                $pages = new Pages();
                $res = $pages->addPage(Post::get());
                $page = $pages->getPage($res);
                $this->view->setStatus($res !== null && $res !== false);
                $page->content = html_entity_decode($page->content);
                $this->view->addData(['page' => $page->toArray()]);
                break;
        }
        $this->view->sendResponse();
    }

    /**
     * @throws \Exception
     */
    private function loadData()
    {
        $langIterator = new \DirectoryIterator('modules/Core/languages');
        $designIterator = new \DirectoryIterator('../style');
        foreach ($designIterator as $design) {
            if ($design->isDir() && $design->isReadable() && !$design->isDot()) {
                $this->assignments['availableDesigns'][] = [$design->getBasename(), file_get_contents($design->getRealPath().DIRECTORY_SEPARATOR.'name.txt')];
            }
        }

        foreach ($langIterator as $lang) {
            if ($lang->isReadable() && $lang->getExtension() == 'csv') {
                $this->assignments['availableLanguages'][] = $lang->getBasename('.csv');
            }
        }
        $profileFields = new ProfileFields();
        $this->assignments['profileFields'] = $profileFields->getAll();
        $this->assignments['fieldTypes'] = ProfileFields::$types;
        $this->assignments['config'] = Cunity::get('config');
    }
}
