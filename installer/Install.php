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

use Cunity\Admin\Models\Process;

require_once(__DIR__ . '/../vendor/autoload.php');

ob_start("ob_gzhandler");
error_reporting(-1);
date_default_timezone_set("UTC");
chdir("..");
session_start();

/**
 * Class Install
 * @package installer
 */
class Install
{
    /**
     *
     * @var String
     */
    private static $lang = "en";

    /**
     *
     * @var array
     */
    private static $langTexts = [];

    /**
     *
     */
    public function __construct()
    {
        $this->init();
        \Cunity\Core\Cunity::init();
        $this->handleRequest();
    }

    /**
     * @throws Exception
     */
    private function init()
    {
        if (file_exists("data/config.xml")) {
//            throw new Exception("Cunity aready installed!");
        }
        if (!file_exists("data/config-example.xml")) {
            throw new Exception("config-example.xml missing!");
        }
        $this->initTranslator();
    }

    /**
     *
     */
    private function initTranslator()
    {
        if (isset($_GET['lang']) && (file_exists("installer/lang/" . $_GET['lang'] . ".php") || $_GET['lang'] == "en")) {
            self::$lang = $_GET['lang'];
            $_SESSION['lang'] = self::$lang;
        } else if (isset($_SESSION['lang']) && (file_exists("installer/lang/" . $_SESSION['lang'] . ".php") || $_SESSION['lang'] == "en")) {
            self::$lang = $_SESSION['lang'];
        } else {
            self::$lang = "en";
        }
        if (self::$lang !== "en") {
            self::$langTexts = include("installer/lang/" . self::$lang . ".php");
        }
    }

    /**
     * @throws Exception
     */
    private function handleRequest()
    {
        if (isset($_REQUEST['action']) &&
            method_exists($this, $_REQUEST['action'])
        ) {
            call_user_func([$this, $_REQUEST['action']]);
        }
    }

    /**
     *
     */
    private function prepareDatabase()
    {
        $connection = @mysqli_connect($_REQUEST['db-host'], $_REQUEST['db-user'], $_REQUEST['db-password'], $_REQUEST['db-name']);

        if ($connection === false) {
            $this->outputAjaxResponse('could not connect to database', false);
        } else {
            $this->executeSql($connection);
        }

        $this->writeDatabaseConfig();
        $this->outputAjaxResponse('');
    }

    /**
     * @param $response
     * @param bool $isSuccess
     */
    private function outputAjaxResponse($response, $isSuccess = true)
    {
        $responseObject = new stdClass();
        $responseObject->success = $isSuccess;
        $responseObject->response = $response;
        echo json_encode($responseObject);
        exit;
    }

    /**
     *
     * @param String $input
     * @internal param array $replacements
     * @return String
     */
    public static function translate($input)
    {
        if (self::$lang == "en" || !(isset(self::$langTexts[$input]))) {
            $str = $input;
        } else {
            $str = self::$langTexts[$input];
        }

        return $str;
    }

    /**
     * @param $connection
     */
    private function executeSql($connection)
    {
        $dbPrefix = $_REQUEST['db-prefix'];

        if ($dbPrefix !== '') {
            $dbPrefix .= '_';
        }

        $sqlData = file_get_contents(__DIR__ . '/../resources/database/newcunity.sql');
        $sqlData = explode(';', str_replace('TABLEPREFIX', $dbPrefix, $sqlData));

        foreach ($sqlData as $query) {
            if ($query !== '') {
                mysqli_query($connection, $query);
            }
        }
    }

    /**
     *
     */
    private function prepareConfig()
    {
        foreach ($_REQUEST['general'] as $setting => $value) {
            $this->writeConfigToDatabase($setting, $value);
        }

        $this->writeConfigToFile($_REQUEST['config']);
    }

    /**
     * @throws Zend_Config_Exception
     */
    private function writeDatabaseConfig()
    {
        $databaseConfig = [];
        $databaseConfig['db'] = [];
        $databaseConfig['db']['params'] = [];
        $databaseConfig['db']['params']['host'] = $_REQUEST['db-host'];
        $databaseConfig['db']['params']['username'] = $_REQUEST['db-user'];
        $databaseConfig['db']['params']['password'] = $_REQUEST['db-password'];
        $databaseConfig['db']['params']['dbname'] = $_REQUEST['db-name'];
        $databaseConfig['db']['params']['table_prefix'] = $_REQUEST['db-prefix'];

        $this->writeConfigToFile($databaseConfig, false);
    }

    /**
     * @param $newConfiguration
     * @param bool $update
     * @throws Zend_Config_Exception
     */
    private function writeConfigToFile($newConfiguration, $update = true)
    {
        $configFile = __DIR__ . "/../data/config-example.xml";

        if ($update) {
            $configFile = __DIR__ . "/../data/config.xml";
        }

        $config = new Zend_Config_Xml($configFile);
        $configWriter = new Zend_Config_Writer_Xml(["config" => new Zend_Config(Process::arrayMergeRecursiveDistinct($config->toArray(), $newConfiguration)), "filename" => __DIR__ . "/../data/config.xml"]);
        $configWriter->write();
    }

    /**
     * @param $field
     * @param $value
     */
    private function writeConfigToDatabase($field, $value)
    {
        $settings = new \Cunity\Core\Models\Db\Table\Settings();
        $settings->setSetting($field, $value);
    }
}

$installer = new Install();

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo Install::translate("Install Cunity"); ?></title>
        <link href="../lib/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../lib/plugins/fontawesome/css/font-awesome.css" rel="stylesheet">
        <link href="../lib/plugins/bootstrap-validator/css/bootstrapValidator.min.css"
              rel="stylesheet">
        <!--[if lt IE 9]>
        <script src="../lib/plugins/js/html5shiv.min.js"></script>
        <script src="../lib/plugins/js/respond.min.js"></script>
        <![endif]-->
        <style>
            .breadcrumb > li + li:before {
                font-family: FontAwesome;
                font-style: normal;
                font-weight: normal;
                line-height: 1;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                padding: 0 10px;
                color: #ccc;
                content: "\f0da" !important;
            }

            .progress {
                margin: 7px 0
            }

            .item {
                margin-right: 0;
                margin-left: 0;
                border: 1px solid #ddd;
                border-radius: 4px;
                -webkit-box-shadow: none;
                box-shadow: none;
                position: relative;
                height: 643px;
                margin-bottom: 20px;
                padding: 50px 20px 20px 20px;
                background-color: #fff;
                overflow-y: auto;
            }

            .item > .title {
                position: absolute;
                top: 15px;
                left: 15px;
                font-size: 12px;
                font-weight: 700;
                color: #959595;
                text-transform: uppercase;
                letter-spacing: 1px;
                cursor: default;
            }

            .item .page-header {
                margin-top: 10px;
            }

            footer {
                text-align: center;
                margin: 30px auto;
                font-style: italic;
                font-size: 0.9em;
                color: #999;
            }

            #steps > li.active > a {
                color: #777 !important;
                text-decoration: none;
                cursor: default;
            }

            .item > form {
                margin: 0 15px;
            }

            .terms div.form-control {
                height: 500px !important;
                overflow-y: scroll;
                overflow-x: hidden;
            }

            #splashscreen {
                width: 350px;
                text-align: center;
                margin-top: 100px;
            }

            #splashscreen img.logo {
                width: 320px;
                padding: 15px;
            }
        </style>
    </head>
    <body>
    <?php if (!isset($_GET['lang'])) { ?>
        <div class="container" id="splashscreen">
            <img src="img/cunity-logo.gif" class="logo">

            <div class="login-container">
                <form>
                    <div class="form-group">
                        <label><?php echo Install::translate("Please select your language for the installation-process"); ?></label>

                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                            <select class="form-control" name="lang">
                                <option value="en">English</option>
                                <option value="de">Deutsch</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-lg btn-block"
                                type="submit"><?php echo Install::translate("Start Installation"); ?></button>
                    </div>
                </form>
            </div>
        </div>
    <?php } else { ?>
        <div class="container" id="installation-container">
        <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
        <div class="page-header">
            <h1><?php echo Install::translate("Install Cunity"); ?></h1>
        </div>
        <div id="installCarousel" class="carousel slide">
        <ol class="breadcrumb" id="steps" role="tablist">
            <li><a href="Install.php" title="<?php echo Install::translate("Back to language selection"); ?>"><i
                        class="fa fa-globe"></i></a></li>
            <li class="active"><a data-target="#installCarousel" href="#terms"
                                  data-slide-to="0"><?php echo Install::translate("Terms"); ?></a></li>
            <li><a data-target="#installCarousel" href="#database"
                   data-slide-to="1"><?php echo Install::translate("Database"); ?></a></li>
            <li><a data-target="#installCarousel" href="#settings"
                   data-slide-to="2"><?php echo Install::translate("Settings"); ?></a></li>
            <li><a data-target="#installCarousel" href="#account"
                   data-slide-to="3"><?php echo Install::translate("Account"); ?></a></li>
            <li><a data-target="#installCarousel" href="#finish"
                   data-slide-to="4"><?php echo Install::translate("Finish"); ?></a></li>
        </ol>

        <div class="carousel-inner">
        <div class="item active" id="terms">
            <span class="title"><?php echo Install::translate("Terms and Conditions"); ?></span>

            <div class="terms">
                <form>
                    <div class="form-group">
                        <label><?php echo Install::translate("Please agree to our Terms & Conditions first"); ?></label>

                        <div class="form-control">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam
                            nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At
                            vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea
                            takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur
                            sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam
                            erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita
                            kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor
                            sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et
                            dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                            et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit
                            amet.

                            Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel
                            illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui
                            blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem
                            ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt
                            ut laoreet dolore magna aliquam erat volutpat.

                            Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl
                            ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in
                            vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero
                            eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue
                            duis dolore te feugait nulla facilisi.

                            Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod
                            mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                            sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut
                            wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut
                            aliquip ex ea commodo consequat.

                            Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel
                            illum dolore eu feugiat nulla facilisis.

                            At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea
                            takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur
                            sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam
                            erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita
                            kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor
                            sit amet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores duo
                            eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet clita ea et
                            gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero voluptua. est Lorem ipsum
                            dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy
                            eirmod tempor invidunt ut labore et dolore magna aliquyam erat.

                            Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore
                            magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea
                            rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam
                            et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est
                            Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed
                            diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
                            voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd
                            gubergren, no sea takimata sanctus.

                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor
                            invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam
                            et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est
                            Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed
                            diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam
                            voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd
                            gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit
                            amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et
                            dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                            et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit
                            amet.

                            Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel
                            illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui
                            blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem
                            ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt
                            ut laoreet dolore magna aliquam erat volutpat.

                            Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl
                            ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in
                            vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero
                            eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue
                            duis dolore te feugait nulla facilisi.

                            Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod
                            mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit,
                            sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut
                            wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut
                            aliquip ex ea commodo
                        </div>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="1" name="accept-terms" id="accept-terms" required="required">
                            <?php echo Install::translate("I accept the Terms and Conditions"); ?>
                        </label>
                    </div>
                </form>
            </div>
        </div>
        <div class="item" id="database">
            <span class="title"><?php echo Install::translate("Setup Database"); ?></span>

            <div class="row">
                <div class="col-lg-7">
                    <form id="databaseForm">
                        <input type="hidden" name="action" value="prepareDatabase"/>
                        <input type="hidden" name="type" value="ajax"/>

                        <div class="form-group">
                            <label for="db-host"><?php echo Install::translate("Database-Host"); ?></label>
                            <input type="text" id="db-host" class="form-control" value="localhost" autocomplete="off"
                                   name="db-host">
                        </div>
                        <div class="form-group">
                            <label for="db-user"><?php echo Install::translate("Database-User"); ?></label>
                            <input type="text" id="db-user" class="form-control" autocomplete="off" name="db-user">
                        </div>
                        <div class="form-group">
                            <label for="db-password"><?php echo Install::translate("Database-Password"); ?></label>
                            <input type="password" id="db-password" class="form-control" autocomplete="off"
                                   name="db-password">
                        </div>
                        <div class="form-group">
                            <label for="db-name"><?php echo Install::translate("Database-Name"); ?></label>
                            <input type="text" id="db-name" class="form-control" autocomplete="off" name="db-name">
                        </div>
                        <div class="form-group">
                            <label for="db-prefix"><?php echo Install::translate("Database-Prefix"); ?></label>
                            <input type="text" id="db-prefix" class="form-control" value="cunity" autocomplete="off"
                                   name="db-prefix">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" id="checkDatabase"><i
                                    class="fa-check fa"></i>&nbsp;<?php echo Install::translate("Check Connection & copy data to database"); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="item" id="settings">
            <span class="title"><?php echo Install::translate("Enter Cunity-Settings"); ?></span>
            <h4 class="page-header"><?php echo Install::translate("General Settings"); ?></h4>

            <form class="form-horizontal" id="configForm">
                <input type="hidden" name="action" value="prepareConfig"/>
                <input type="hidden" name="type" value="ajax"/>
                <div class="form-group">
                    <label class="col-lg-3 control-label"
                           for="sitename"><?php echo Install::translate("Name of your Cunity"); ?></label>

                    <div class="col-lg-7">
                        <input type="text" name="general[core.sitename]" id="sitename" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"
                           for="siteurl"><?php echo Install::translate("URL of your Cunity"); ?></label>

                    <div class="col-lg-7">
                        <input type="text" name="general[core.siteurl]" id="siteurl" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"
                           for="description"><?php echo Install::translate("Description"); ?></label>

                    <div class="col-lg-7">
                        <textarea class="form-control" id="description" name="general[core.description]"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"
                           for="contactmail"><?php echo Install::translate("Contact Mail"); ?></label>

                    <div class="col-lg-7">
                        <input type="text" name="general[core.contact_mail]" id="contactmail" class="form-control">
                    </div>
                </div>
            <h4 class="page-header"><?php echo Install::translate("Mail Settings"); ?></h4>

                <div class="form-group">
                    <label for="use-smtp"
                           class="col-lg-3 control-label"><?php echo Install::translate("Mailserver"); ?></label>

                    <div class="col-lg-7">
                        <div class="radio-inline">
                            <label>
                                <input type="radio" id="connection-type-smtp" required name="config[mail][smtp]"
                                       class="change-connection-type">&nbsp;<?php echo Install::translate("Use SMTP"); ?>
                            </label>
                        </div>
                        <div class="radio-inline">
                            <label>
                                <input type="radio" id="connection-type-sendmail" required name="config[mail][smtp]"
                                       class="change-connection-type">&nbsp;<?php echo Install::translate("Use PHP Sendmail"); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div id="smtp-settings">
                    <div class="form-group">
                        <label for="smtp-host"
                               class="col-lg-3 control-label"><?php echo Install::translate("SMTP-Host"); ?></label>

                        <div class="col-lg-7">
                            <input type="text" class="form-control"
                                   id="smtp-host" name="config[mail][params][host]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="smtp-port"
                               class="col-lg-3 control-label"><?php echo Install::translate("SMTP-Port"); ?></label>

                        <div class="col-lg-7">
                            <input type="number" class="form-control"
                                   id="smtp-port" name="config[mail][params][port]" required data-bv-greaterthan
                                   data-bv-greaterthan-value="25">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="smtp-auth"
                               class="col-lg-3 control-label"><?php echo Install::translate("SMTP-Authentication"); ?></label>

                        <div class="col-lg-7">
                            <select class="form-control" id="smtp-auth" name="config[mail][params][auth]" required>
                                <option value="login"><?php echo Install::translate("Yes"); ?></option>
                                <option value="plain"><?php echo Install::translate("No"); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="smtp-username"
                               class="col-lg-3 control-label"><?php echo Install::translate("SMTP-Username"); ?></label>

                        <div class="col-lg-7">
                            <input type="text" required class="form-control"
                                   id="smtp-username"
                                   name="config[mail][params][username]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="smtp-password"
                               class="col-lg-3 control-label"><?php echo Install::translate("SMTP-Password"); ?></label>

                        <div class="col-lg-7">
                            <input type="password" required class="form-control"
                                   id="smtp-password"
                                   name="config[mail][params][password]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="smtp-ssl"
                               class="col-lg-3 control-label"><?php echo Install::translate("SMTP-Security"); ?></label>

                        <div class="col-lg-7">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="config[mail][params][ssl]"
                                           value="ssl">&nbsp;<?php echo Install::translate("Use SSL"); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="item" id="account">
            <span class="title"><?php echo Install::translate("Create Admin-Account"); ?></span>

            <form class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-lg-3"
                           for="input-username"><?php echo Install::translate("Username"); ?></label>

                    <div class="col-lg-7">
                        <input type="text" autocomplete="off" required class="form-control" id="input-username"
                               placeholder="<?php echo Install::translate("Username"); ?>" name="username">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3"
                           for="input-email"><?php echo Install::translate("E-Mail"); ?></label>

                    <div class="col-lg-7">
                        <input type="email" required class="form-control" id="input-email"
                               placeholder="<?php echo Install::translate("E-Mail"); ?>"
                               name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3"
                           for="input-firstname"><?php echo Install::translate("Firstname"); ?></label>

                    <div class="col-lg-7">
                        <input type="text" autocomplete="off" required class="form-control" id="input-firstname"
                               placeholder="<?php echo Install::translate("Firstname"); ?>" name="firstname">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3"
                           for="input-lastname"><?php echo Install::translate("Lastname"); ?></label>

                    <div class="col-lg-7">
                        <input type="text" autocomplete="off" required class="form-control" id="input-lastname"
                               placeholder="<?php echo Install::translate("Lastname"); ?>" name="lastname">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3"
                           for="input-password"><?php echo Install::translate("Password"); ?></label>

                    <div class="col-lg-7">
                        <input type="password" autocomplete="off" required class="form-control" id="input-password"
                               placeholder="<?php echo Install::translate("Password"); ?>" name="password">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-3"
                           for="input-password-repeat"><?php echo Install::translate("Repeat password"); ?></label>

                    <div class="col-lg-7">
                        <input type="password" autocomplete="off" required class="form-control"
                               id="input-password-repeat"
                               placeholder="<?php echo Install::translate("Repeat password"); ?>"
                               name="password_repeat">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:10px">
                    <label class="control-label col-lg-3"><?php echo Install::translate("I am"); ?></label>

                    <div class="col-lg-7">
                        <select class="form-control" name="sex" required>
                            <option value=""><?php echo Install::translate("Select your gender"); ?></option>
                            <option value="f"><?php echo Install::translate("Female"); ?></option>
                            <option value="m"><?php echo Install::translate("Male"); ?></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="item" id="finish">
            <span class="title"><?php echo Install::translate("Finish Installation"); ?></span>
        </div>
        </div>
        </div>
        <div class="row">
            <div class="col-lg-10">
                <div class="progress">
                    <div class="progress-bar" id="installation-progress" role="progressbar" aria-valuenow="0"
                         aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                        <span class="progress-status">0</span>%
                    </div>
                </div>
            </div>
            <div class="col-lg-2 clearfix"><a role="button" href="#installCarousel" id="installNextButton"
                                              data-slide="next" disabled
                                              class="btn btn-primary pull-right"><?php echo Install::translate("Next"); ?>
                    &nbsp;<i class="fa fa-chevron-right"></i></a></div>
            <div class="col-lg-2 clearfix"><a role="button" href=".." id="installFinishButton" disabled
                                              class="btn btn-success pull-right hidden"><i class="fa fa-check"></i>&nbsp;<?php echo Install::translate("Finish"); ?>
                </a></div>
        </div>
        </div>
        </div>
        </div>
        </div>
    <?php } ?>
    <footer>
        &copy; 2014 Smart In Media GmbH & Co. KG
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../lib/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../lib/plugins/bootstrap-validator/js/bootstrapValidator.min.js" type="text/javascript"></script>
    <script>
        var origShow = jQuery.fn.show, origHide = jQuery.fn.hide;
        jQuery.fn.show = function () {
            $(this).removeClass("hidden");
            return origShow.apply(this, arguments);
        };
        jQuery.fn.hide = function () {
            $(this).addClass("hidden");
            return origHide.apply(this, arguments);
        };
        $(document).ready(function () {
            $('#installCarousel').carousel({
                interval: false
            });
            $('#accept-terms').change(function () {
                if ($('#accept-terms').is(':checked')) {
                    $('#installNextButton').removeAttr('disabled');
                } else {
                    $('#installNextButton').attr('disabled', 'disabled');
                }
            });
            index = 1;

            $('#checkDatabase').click(function () {
                $.ajax({
                    type: "GET",
                    url: '<?php echo $_SERVER["PHP_SELF"] ?>',
                    data: $('#databaseForm').serialize()
                }).done(function (data) {
                    data = $.parseJSON(data);
                    if (data.success) {
                        $('#installCarousel').carousel('next');
                    }
                });

                return false;
            });

            $('#installNextButton').click(function() {
                console.log(index);
                if (index == 3) {
                    $.ajax({
                        type: "GET",
                        url: '<?php echo $_SERVER["PHP_SELF"] ?>',
                        data: $('#configForm').serialize()
                    });
                }
            });

            $('#installCarousel').on('slide.bs.carousel', function (e) {
                var c = $(this).data('bs.carousel');
                var oldIndex = index;
                index = $("#installCarousel .item").index($(e.relatedTarget)) + 1;

                $("#steps > li.active").removeClass("active");
                $("#steps > li:eq(" + index + ")").addClass("active");
                var percentage = (100 / ($("#steps > li").length - 1)) * index;
                if (percentage == 100) {
                    $("#installation-progress").addClass("progress-bar-success");
                }
                else {
                    $("#installation-progress").removeClass("progress-bar-success");
                }
                $("#installation-progress").width(percentage + "%").attr("aria-valuenow", percentage).children(".progress-status").text(Math.round(percentage));
                $("#installNextButton, #installPrevButton, #installFinishButton").hide();

                if (index > 1) {
                    $("#installPrevButton").show();
                }
                if (index < $("#steps > li").length - 1) {
                    $("#installNextButton").show();
                }
                if (index == $("#steps > li").length - 1) {
                    $("#installFinishButton").show();
                }

                if (index > oldIndex && index != 3) {
                    $('#installNextButton').attr('disabled', 'disabled');
                } else {
                    $('#installNextButton').removeAttr('disabled');
                }
            });
        });
    </script>
    </body>
    </html>

<?php

ob_end_flush();
