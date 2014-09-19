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

namespace Comments\Models;

use Core\View\Ajax\View;

/**
 * Class Comments
 * @package Comments\Models
 */
class Comments
{

    /**
     * @var Db\Table\Comments
     */
    /**
     * @var Db\Table\Comments|\Core\View\Ajax\View
     */
    private $table, $view;

    /**
     * @param $action
     */
    public function __construct($action)
    {
        $this->view = new View();
        if (!isset($_POST['ref_name']) || !isset($_POST['ref_id'])) {
            $this->view->setStatus(false);
        } else {
            $this->table = new Db\Table\Comments();
            if (method_exists($this, $action))
                call_user_func([$this, $action]);
        }
        $this->view->sendResponse();
    }

    /**
     *
     */
    private function add()
    {
        $res = $this->table->addComment($_POST['ref_id'], $_POST['ref_name'], $_POST['content']);
        $this->view->setStatus($res !== false);
        $this->view->addData($res);
    }

    /**
     *
     */
    private function remove()
    {
        $res = $this->table->removeComment($_POST['comment_id']);
        $this->view->setStatus($res !== false);
    }

    /**
     *
     */
    private function removeAll()
    {
        $res = $this->table->removeAllComments($_POST['ref_id'], $_POST['ref_name']);
        $this->view->setStatus($res !== false);
    }

    /**
     *
     */
    private function get()
    {
        $res = $this->table->get($_POST['ref_id'], $_POST['ref_name'], $_POST['last'], $_POST['limit']);
        $this->view->setStatus($res !== false);
        $this->view->addData(["comments" => $res]);
    }

}
