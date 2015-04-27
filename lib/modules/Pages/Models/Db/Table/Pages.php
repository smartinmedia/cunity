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

namespace Cunity\Pages\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Pages.
 */
class Pages extends Table
{
    /**
     * @var string
     */
    protected $_name = 'pages';
    /**
     * @var string
     */
    protected $_primary = 'shortlink';
    /**
     * @var string
     */
    protected $_rowClass = "\Cunity\Pages\Models\Db\Row\Page";

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $shortlink
     *
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getPage($shortlink)
    {
        return $this->fetchRow($this->select()->where('shortlink=?', $shortlink));
    }

    /**
     * @param $id
     *
     * @return null|\Zend_Db_Table_Row_Abstract
     */
    public function getPageById($id)
    {
        return $this->fetchRow($this->select()->where('id=?', intval($id)));
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function addPage(array $data)
    {
        $returnValue = false;

        if (isset($data['pageid']) && $data['pageid'] > 0) {
            if (false !== $this->update(
                    [
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'comments' => isset($data['comments']) ? 1 : 0,
                    'shortlink' => preg_replace('/[^a-zA-Z0-9\-]/', '', $data['title']),
                ],
                    'id='.$data['pageid'])
            ) {
                $returnValue = preg_replace('/[^a-zA-Z0-9\-]/', '', $data['title']);
            }
        } else {
            $returnValue = $this->insert([
                'title' => $data['title'],
                'content' => $data['content'],
                'comments' => isset($data['comments']) ? 1 : 0,
                'shortlink' => preg_replace('/[^a-zA-Z0-9\-]/', '', $data['title']),
            ]);
        }

        return $returnValue;
    }

    /**
     * @param $pageid
     *
     * @return bool
     */
    public function deletePage($pageid)
    {
        return ($this->delete($this->getAdapter()->quoteInto('id=?', $pageid)) > 0);
    }

    /**
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function loadPages()
    {
        $res = $this->fetchAll();
        foreach ($res as $page) {
            $page->content = html_entity_decode($page->content);
        }

        return $res;
    }
}
