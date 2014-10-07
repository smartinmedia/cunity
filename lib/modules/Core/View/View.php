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

namespace Cunity\Core\View;

use Cunity\Core\Cunity;
use Cunity\Core\Exception;
use Cunity\Core\Models\Db\Table\Announcements;
use Cunity\Core\Models\Db\Table\Menu;
use Cunity\Core\Models\Generator\Url;
use Cunity\Register\Models\Login;
use Smarty;
use Zend_Log;
use Zend_Log_Writer_Stream;

/**
 * Class View
 * @package Core\View
 */
class View extends Smarty
{
    /**
     * @var
     */
    public static $zt;
    /**
     * @var string
     */
    protected $_templateRoot = "modules/";
    /**
     * @var mixed|string
     */
    protected $_coreRoot = "../style/%design%/";
    /**
     * @var string
     */
    protected $_templateCache = "../data/temp/templates-cache/";
    /**
     * @var string
     */
    protected $_templateCompiled = "../data/temp/templates-compiled/";
    /**
     * @var bool
     */
    protected $_useWrapper = true;
    /**
     * @var string
     */
    protected $_wrapper = "Core/styles/out_wrap.tpl";
    /**
     * @var string
     */
    protected $_templateDir = "";
    /**
     * @var string
     */
    protected $_templateFile = "";
    /**
     * @var string
     */
    protected $_languageFolder = "Core/languages/";
    /**
     * @var array
     */
    protected $_headScripts = [];
    /**
     * @var array
     */
    protected $_headCss = [];
    /**
     * @var array
     */
    protected $_metadata = [
        "title" => "A page",
        "description" => "Cunity - Your private social network"
    ];

    /**
     * @throws \Exception
     * @throws \SmartyException
     */
    public function __construct()
    {
        parent::__construct();
        if (!file_exists("../style/" . $this->getSetting("core.design"))) {
            throw new \Exception(
                "Cannot find Theme-Folder \""
                . $this->getSetting("core.design")
                . "\""
            );
        }
        $this->_coreRoot = str_replace(
            "%design%",
            $this->getSetting("core.design"),
            $this->_coreRoot
        );
        // $this->use_include_path = true;
        $this->setTemplateDir([$this->_coreRoot, $this->_templateRoot]);
        $this->setCompileDir($this->_templateCompiled);
        $this->setCacheDir($this->_templateCache);
        $this->left_delimiter = "{-";
        $this->debugging = Cunity::get("config")->site->tpl_debug;
        $this->registerPlugin("modifier", "translate", [$this, "translate"]);
        $this->registerPlugin("modifier", "setting", [$this, "getSetting"]);
        $this->registerPlugin("modifier", "config", [$this, "getConfig"]);
        $this->registerPlugin("modifier", "image", [$this, "convertImage"]);
        $this->registerPlugin("modifier", "URL", [$this, 'convertUrl']);
        $this->_templateDir = ucfirst($this->_templateDir);
        $this->initTranslator();
    }

    /**
     * @param $settingname
     * @return mixed
     * @throws \Exception
     */
    public function getSetting($settingname)
    {
        return Cunity::get("settings")->getSetting($settingname);
    }

    /**
     * @param $urlString
     * @return string
     */
    public static function convertUrl($urlString)
    {
        return Url::convertUrl($urlString);
    }

    /**
     * @param $string
     * @param array $replaces
     * @return string
     */
    public static function translate($string, $replaces = [])
    {
        return vsprintf($string, $replaces);
    }

    /**
     * @param array $meta
     */
    public function setMetaData(array $meta)
    {
        $this->_metadata = $meta;
    }

    /**
     * @param $filename
     * @param $type
     * @param string $prefix
     * @return string
     */
    public function convertImage($filename, $type, $prefix = "")
    {
        if ($filename == null || empty($filename)) {
            return $this->getSetting("core.siteurl")
            . "style/"
            . $this->getSetting("core.design")
            . "/img/placeholders/noimg-"
            . $type . ".png";
        }
        return $this->getSetting("core.siteurl")
        . "data/uploads/"
        . $this->getSetting("core.filesdir")
        . "/"
        . $prefix
        . $filename;
    }

    /**
     * @param $value
     */
    public function useWrapper($value)
    {
        $this->_useWrapper = ($value === true);
    }

    /**
     * @throws \Cunity\Core\Exception
     * @throws \Exception
     */
    public function show()
    {
        if (Login::loggedIn() && $_GET['m'] !== "admin") {
            $this->registerScript("search", "livesearch");
            $this->registerScript("messages", "message-modal");
            $this->registerScript("notifications", "notifications");
            $this->registerScript("messages", "chat");
            $this->registerCss("messages", "chat");
            $this->registerCss("search", "livesearch");
            $this->registerCss("messages", "message-modal");
            $this->registerCss("notifications", "notifications");
            $this->registerScript("friends", "friends");
        }
        $announcements = new Announcements();
        $this->assign("announcements", $announcements->getAnnouncements());
        if ((Login::loggedIn())) {
            $this->assign(
                'user',
                $_SESSION['user']->getTable()->get($_SESSION['user']->userid)
            );
        } else {
            $this->assign('user', []);
        }
        $this->assign('menu', new Menu());
        $this->registerCunityPlugin(
            "jscrollpane",
            ["css/jquery.jscrollpane.css", "js/jquery.jscrollpane.min.js"]
        );
        $this->registerCunityPlugin(
            "bootstrap-validator",
            [
                "css/bootstrapValidator.min.css",
                "js/bootstrapValidator.min.js"
            ]
        );
        $this->_metadata["module"] = $_GET['m'];
        $this->assign("meta", $this->_metadata);
        $this->assign('script_head', implode("\n", $this->_headScripts));
        $this->assign('css_head', base64_encode(implode(",", $this->_headCss)));
        $this->assign('modrewrite', (boolean)Cunity::get("mod_rewrite"));
        if ($this->_useWrapper) {
            $this->assign(
                'tpl_name',
                $this->_templateDir . '/styles/' . $this->_templateFile
            );
            $this->display($this->_wrapper);
        } else {
            $this->display(
                $this->_templateDir
                . DIRECTORY_SEPARATOR
                . 'styles'
                . DIRECTORY_SEPARATOR
                . $this->_templateFile
            );
        }
    }

    /**
     * @param $module
     * @param $scriptName
     * @throws Exception
     */
    protected function registerScript($module, $scriptName)
    {
        if ((!empty($module))) {
            $module = ucfirst($module) . "/styles/javascript/";
        } else {
            $module = "../plugins/javascript/";
        }

        if (file_exists(
            $this->_templateRoot . $module . $scriptName . ".min.js"
        )) {
            $this->_headScripts[] = '<script src="'
                . $this->getSetting("core.siteurl")
                . "lib/"
                . $this->_templateRoot
                . $module
                . $scriptName
                . ".min.js"
                . '"></script>';
        } elseif (file_exists(
            $this->_templateRoot . $module . $scriptName . ".js"
        )) {
            $this->_headScripts[] = '<script src="'
                . $this->getSetting("core.siteurl")
                . "lib/"
                . $this->_templateRoot
                . $module
                . $scriptName
                . ".js"
                . '"></script>';
        } else {
            throw new Exception(
                "Cannot load javascript-file: '"
                . $this->_templateRoot
                . $module
                . $scriptName
                . ".js"
                . "'"
            );
        }
    }

    /**
     * @param $module
     * @param $fileName
     * @throws Exception
     */
    protected function registerCss($module, $fileName)
    {
        if ((!empty($module))) {
            $module = ucfirst($module) . "/styles/css/";
        } else {
            $module = "../plugins/css/";
        }

        if (file_exists($this->_templateRoot . $module . $fileName . ".css")) {
            $this->_headCss[] = $module . $fileName . ".css";
        } else {
            throw new Exception(
                "Cannot load CSS-file: '"
                . $this->_templateRoot
                . $module
                . $fileName
                . ".css"
                . "'"
            );
        }
    }

    /**
     * @param $pluginName
     * @param array $files
     */
    protected function registerCunityPlugin($pluginName, array $files)
    {
        if (file_exists("plugins/" . $pluginName)) {
            if (!empty($files)) {
                foreach ($files as $file) {
                    $finfo = pathinfo($file);
                    if ($finfo['extension'] == "js") {
                        $this->_headScripts[] = '<script src="'
                            . $this->getSetting("core.siteurl")
                            . "lib/plugins/" . $pluginName
                            . "/"
                            . $file
                            . '"></script>';
                    } elseif ($finfo['extension'] == "css") {
                        $this->_headCss[] = "../plugins/"
                            . $pluginName
                            . "/"
                            . $file;
                    }
                }
            }
        }
    }

    /**
     *
     */
    private function initTranslator()
    {
        $locale = new \Zend_Locale();
        self::$zt = new \Zend_Translate(
            [
                'adapter' => 'gettext',
                'locale' => 'auto',
                'content' => "Core/languages/",
                'scan' => \Zend_Translate::LOCALE_FILENAME
            ]
        );
        self::$zt->setOptions(
            [
                'log' => new Zend_Log(
                    new Zend_Log_Writer_Stream('missing-translations.log')
                ),
                'logUntranslated' => true
            ]
        );

        if (!self::$zt->isAvailable($locale->getLanguage())) {
            self::$zt->setLocale(self::$defaultLanguage);
        }
        self::$zt->getLocale();
    }
}
