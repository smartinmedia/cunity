<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2015 Smart In Media GmbH & Co. KG                            ##
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

use Cunity\Core\Exceptions\Exception;
use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class Settings.
 */
class Settings extends Table
{
    /**
     * @var string
     */
    protected $_name = 'settings';
    /**
     * @var string
     */
    protected $_primary = 'name';
    /**
     * @var string
     */
    protected $_rowClass = "\Cunity\Core\Models\Db\Row\Setting";
    /**
     * @var array
     */
    private $settings = [];

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
    public function getSettings()
    {
        return $this->fetchAll();
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function __get($name)
    {
        return $this->getSetting($name);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return bool
     *
     * @throws Exception
     */
    public function __set($name, $value)
    {
        return $this->setSetting($name, $value);
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function getSetting($name)
    {
        if (isset($this->settings[$name])) {
            return $this->settings[$name];
        }
        $row = $this->fetchRow($this->select()->where('name=?', $name));
        $this->settings[$name] = $row->value;

        return $row->value;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return bool
     *
     * @throws Exception
     */
    public function setSetting($name, $value)
    {
        $row = $this->fetchRow($this->select()->where('name=?', $name));

        if ($row === null) {
            $insertStatus = $this->insert(['name' => $name, 'value' => $value]);

            return $insertStatus;
        } else {
            $row->value = $value;

            return (false !== $row->save());
        }
    }
}
