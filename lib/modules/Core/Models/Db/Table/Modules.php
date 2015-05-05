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

namespace Cunity\Core\Models\Db\Table;

use Cunity\Core\Exceptions\DirectoryNotWriteable;
use Cunity\Core\Exceptions\FileNotWriteable;
use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Modules.
 */
class Modules extends Table
{
    /**
     * @var string
     */
    protected $_name = 'modules';

    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Zend_Db_Table_Rowset_Abstract
     */
    public function getModules()
    {
        return $this->fetchAll();
    }

    /**
     * @param $moduletag
     * @param bool $onlyActive
     *
     * @return \Zend_Db_Table_Row_Abstract
     */
    public function getModuleData($moduletag, $onlyActive = true)
    {
        /** @var \Zend_Db_Table_Select $where */
        $where = $this->select()->where('namespace=?', $moduletag)->limit(1);

        if ($onlyActive) {
            $where->where('status = ?', 1);
        }

        return $this->fetchRow($where);
    }

    /**
     * @param array|string $where
     *
     * @return int
     *
     * @throws DirectoryNotWriteable
     * @throws FileNotWriteable
     */
    public function delete($where)
    {
        $module = new Modules();
        $moduleData = $module->fetchRow($where);
        $moduleDirectory = __DIR__.'/../../../../'.ucfirst($moduleData->namespace);
        $directory = new \RecursiveDirectoryIterator($moduleDirectory, \RecursiveIteratorIterator::CHILD_FIRST);
        $iterator = new \RecursiveIteratorIterator($directory);

        /** @var \SplFileInfo $object */
        foreach ($iterator as $object) {
            if ($object->isDir()) {
                continue;
            }

            if (!unlink($object->getPathname())) {
                throw new FileNotWriteable();
            }
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($moduleDirectory, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);

        /** @var \SplFileInfo $object */
        foreach ($iterator as $object) {
            if (!rmdir($object->getPathname())) {
                throw new DirectoryNotWriteable();
            }
        }

        if (!rmdir($moduleDirectory)) {
            throw new DirectoryNotWriteable();
        }

        return parent::delete($where);
    }
}
