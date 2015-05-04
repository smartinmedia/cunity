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

namespace Cunity\Admin\Helper;

use Cunity\Admin\Models\Process;
use Cunity\Admin\Models\Updater\DatabaseUpdater;
use Cunity\Core\Cunity;
use Cunity\Core\Helper\UserHelper;
use ZipArchive;

/**
 * Class UpdateHelper.
 */
class UpdateHelper
{
    /**
     * @var string
     */
    public static $UPDATECHECKURL = 'http://server.cunity.net/version.php';

    /**
     * @var string
     */
    public static $LATESTURL = 'http://server.cunity.net/latest.zip';

    /**
     * @var int
     */
    protected static $TIMEOUT = 3;

    /**
     * @return mixed
     */
    public static function hasUpdates()
    {
        if (!UserHelper::isAdmin()) {
            return false;
        }

        return version_compare(self::getVersion(), self::getRemoteVersion(), '<');
    }

    /**
     * @return string
     */
    protected static function updateServerAvailable()
    {
        $errorReporting = error_reporting();
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
        $xcontext = stream_context_create(['http' => ['timeout' => self::$TIMEOUT]]);
        $test = file_get_contents(self::$UPDATECHECKURL, 'r', $xcontext);
        error_reporting($errorReporting);

        return $test;
    }

    /**
     * @return mixed
     *
     * @throws \Cunity\Core\Exceptions\Exception
     */
    public static function getVersion()
    {
        $config = Cunity::get('config');

        return $config->site->version;
    }

    /**
     * @param bool $force
     *
     * @return mixed
     *
     * @throws \Cunity\Core\Exceptions\InstanceNotFound
     */
    protected static function getRemoteVersion($force = false)
    {
        $settings = Cunity::get('settings');

        if ($settings->getSetting('core.lastupdatecheck') < mktime(0, 0, 1, date('m'), date('d'), date('Y')) ||
            $force
        ) {
            if (!self::updateServerAvailable()) {
                $newVersion = self::getVersion();
            } else {
                $context = array('http' => array(
                    'header' => 'Referer: '.
                        $settings->getSetting('core.siteurl'),));
                $xcontext = stream_context_create($context);
                $newVersion = file_get_contents(self::$UPDATECHECKURL, 'r', $xcontext);
            }

            $settings->setSetting('core.remoteversion', $newVersion);
            $settings->setSetting('core.lastupdatecheck', time());
        }

        return $settings->getSetting('core.remoteversion');
    }

    /**
     *
     */
    public static function update()
    {
        set_time_limit(0);
        $updateFile = self::getUpdateFile();
        self::unpackUpdateFile($updateFile);
        self::updateConfigFile();
        new DatabaseUpdater();
        self::getRemoteVersion(true);
    }

    /**
     * @return string
     */
    private static function getUpdateFile()
    {
        $ch = curl_init(self::$LATESTURL);
        $updateFile = __DIR__.'/../../../../data/temp/latest.zip';
        $targetFile = fopen($updateFile, 'w+');
        curl_setopt($ch, CURLOPT_FILE, $targetFile);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_exec($ch);
        fclose($targetFile);

        return $updateFile;
    }

    /**
     * @param $updateFile
     */
    private static function unpackUpdateFile($updateFile)
    {
        $zip = new ZipArchive();
        $zip->open($updateFile);
        $zip->extractTo(__DIR__.'/../../../../');
        $zip->close();
    }

    /**
     * @throws \Zend_Config_Exception
     */
    private static function updateConfigFile()
    {
        $configuration = [];
        $configuration['site'] = [];
        $configuration['site']['version'] = self::getRemoteVersion();
        $config = new \Zend_Config_Xml(__DIR__.'/../../../../data/config.xml');
        $configWriter = new \Zend_Config_Writer_Xml(['config' => new \Zend_Config(Process::arrayMergeRecursiveDistinct($config->toArray(), $configuration)), 'filename' => __DIR__.'/../../../../data/config.xml']);
        $configWriter->write();
    }
}
