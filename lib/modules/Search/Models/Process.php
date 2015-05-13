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

namespace Cunity\Search\Models;

use Cunity\Core\Models\Db\Table\Users;
use Cunity\Core\Request\Post;
use Cunity\Core\Request\Session;

/**
 * Class Process.
 */
class Process
{
    /**
     * @var string
     */
    private $indexfile = '../../../data/searchindex';

    /**
     *
     */
    public function __construct()
    {
        $this->indexfile = __DIR__.'/'.$this->indexfile;
    }

    /**
     * @param $queryString
     *
     * @return array
     */
    public function find($queryString)
    {
        $queryString = trim($queryString);
        if (empty($queryString)) {
            return ['queryString' => $queryString, 'message' => 'No String'];
        } else {
            $index = \Zend_Search_Lucene::open($this->indexfile);
            $res = explode(' ', $queryString);
            \Zend_Search_Lucene_Search_Query_Wildcard::setMinPrefixLength(1);
            \Zend_Search_Lucene::setResultSetLimit(5);
            $query = new \Zend_Search_Lucene_Search_Query_Boolean();
            foreach ($res as $val) {
                if (!empty($val)) {
                    $subquery = new \Zend_Search_Lucene_Search_Query_Boolean();
                    $searchkey1 = $val.'*';
                    $pattern = new \Zend_Search_Lucene_Index_Term($searchkey1, 'name');
                    $userQuery = new \Zend_Search_Lucene_Search_Query_Wildcard($pattern);
                    $patternUsername = new \Zend_Search_Lucene_Index_Term($searchkey1, 'username');
                    $usernameQuery = new \Zend_Search_Lucene_Search_Query_Wildcard($patternUsername);
                    $subquery->addSubquery($userQuery, null);
                    $subquery->addSubquery($usernameQuery, null);
                    $query->addSubquery($subquery, true);
                }
            }
            $hits = $index->find($query);
            if (!empty($hits)) {
                $results = [];
                foreach ($hits as $hit) {
                    if ($hit->username != Session::get('user')->username) {
                        $results[] = $hit->username;
                    }
                }
                if (!empty($results)) {
                    /** @var Users $users */
                    $users = Session::get('user')->getTable();
                    if (Post::get('friends') !== null) {
                        $friends = Session::get('user')->getFriendList();
                        if (empty($friends)) {
                            return ['queryString' => $queryString, 'users' => []];
                        } else {
                            $userresult = $users->getSet($results, 'u.username');
                        }
                    } else {
                        $userresult = $users->getSet($results, 'u.username');
                    }

                    return ['queryString' => $queryString, 'users' => $userresult->toArray()];
                }
            }
        }

        return ['queryString' => $queryString];
    }

    /**
     * @param $username
     * @param $newusername
     * @param $newname
     *
     * @return bool
     */
    public function updateUser($username, $newusername, $newname)
    {
        $index = \Zend_Search_Lucene::open($this->indexfile);
        $hits = $index->find('username:'.$username);

        foreach ($hits as $hit) {
            $index->delete($hit->id);
        }

        return $this->addUser($newusername, $newname);
    }

    /**
     * @param $username
     * @param $name
     *
     * @return bool
     */
    public function addUser($username, $name)
    {
        try {
            $index = \Zend_Search_Lucene::open($this->indexfile);
        } catch (\Zend_Search_Lucene_Exception $e) {
            $index = \Zend_Search_Lucene::create($this->indexfile);
        }

        $doc = new \Zend_Search_Lucene_Document();
        $doc->addField(\Zend_Search_Lucene_Field::Text('username', $username));
        $doc->addField(\Zend_Search_Lucene_Field::unStored('name', $name));
        $index->addDocument($doc);
        $index->optimize();

        return true;
    }

    /**
     * @param $username
     */
    public function removeUser($username)
    {
        $index = \Zend_Search_Lucene::open($this->indexfile);
        $hits = $index->find('username:'.$username);
        foreach ($hits as $hit) {
            $index->delete($hit->id);
        }
    }

    /**
     * @return bool
     */
    public function optimize()
    {
        try {
            $index = \Zend_Search_Lucene::open($this->indexfile);
        } catch (\Zend_Search_Lucene_Exception $e) {
            $index = \Zend_Search_Lucene::create($this->indexfile);
        }
        $index->optimize();

        return true;
    }

    /**
     * @return bool
     */
    public function recreateSearchIndex()
    {
        $users = new Users();
        try {
            $index = \Zend_Search_Lucene::open($this->indexfile);
        } catch (\Zend_Search_Lucene_Exception $e) {
            $index = \Zend_Search_Lucene::create($this->indexfile);
        }
        $all = $users->getSet([]);
        foreach ($all as $user) {
            $doc = new \Zend_Search_Lucene_Document();
            $doc->addField(\Zend_Search_Lucene_Field::Text('username', $user->username));
            $doc->addField(\Zend_Search_Lucene_Field::unStored('name', $user->name));
            $index->addDocument($doc);
        }
        $index->optimize();

        return true;
    }
}
