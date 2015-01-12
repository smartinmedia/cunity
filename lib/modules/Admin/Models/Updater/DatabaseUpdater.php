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

namespace Cunity\Admin\Models\Updater;

use Cunity\Admin\Models\Db\Table\Versions;
use Cunity\Core\Cunity;
use Cunity\Core\View\Message;
use Cunity\Search\Models\Process;

/**
 * Class DatabaseUpdater
 * @package Admin\Models\Updater
 */
class DatabaseUpdater
{

    /**
     *
     * @var String
     */
    protected $_directory = "../data/temp/databaseUpdates";

    /**
     *
     * @var array
     */
    protected $versions = [];

    /**
     *
     * @var \Cunity\Admin\Models\Db\Table\Versions
     */
    protected $versionDb = null;

    /**
     *
     */
    public function __construct()
    {
        $this->init();
        $this->run();
    }

    /**
     *
     */
    private function init()
    {
        $this->versionDb = new Versions();
        $v = $this->versionDb->getVersions();
        $this->versions = ($v !== false) ? $v : [];
    }

    /**
     * @throws \Cunity\Core\Exception
     */
    public function run()
    {
        $dir = new \DirectoryIterator($this->_directory);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isReadable()) {
                if ($fileinfo->getExtension() == "php") {
                    include_once $this->_directory . DIRECTORY_SEPARATOR . $fileinfo->getFilename();
                    $classname = $fileinfo->getBasename(".php");
                    $classnameParts = explode('Version', $classname);
                    if (class_exists($classname) && !$this->versionInstalled($classnameParts[1])) {
                        /** @var DbUpdateVersion $dbCmd */
                        $dbCmd = new $classname(Cunity::get("db"));
                        if ($dbCmd instanceof DbCommandInterface) {
                            /** @noinspection PhpUndefinedMethodInspection */
                            $dbCmd->execute();
                            $dbCmd->updateDatabaseTimestamp($this->versionDb);
                        }
                    }
                }
            }
        }

        $process = new Process();
        $process->recreateSearchIndex();
    }

    /**
     *
     * @param double $timestamp
     * @return boolean
     */
    protected function versionInstalled($timestamp)
    {
        return in_array($timestamp, $this->versions);
    }
}
