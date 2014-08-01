<?php
ob_start("ob_gzhandler");
error_reporting(-1);
date_default_timezone_set("UTC");
chdir("..");
session_start("cunity-installer");

/**
 * Class Install
 * @package installer
 */
class Install {

    /**
     *
     * @var array
     */
    private $steps = [];

    /**
     *
     * @var String
     */
    private $lang = "en";

    /**
     *
     * @var array
     */
    private $langTexts = [];

    /**
     * 
     */
    public function __construct() {
        $this->init();
        $this->handleRequest();
    }

    /**
     * @throws Exception
     */
    private function init() {
//        if (file_exists("data/config.xml")) {
//            throw new Exception("Cunity aready installed!");
//        }
        if (!file_exists("data/config-example.xml")) {
            throw new Exception("config-example.xml missing!");
        }
        @include 'Zend/Version.php';
        @include 'Smarty/Smarty.class.php';
        $this->initTranslator();
    }

    /**
     * 
     */
    private function initTranslator() {
        if (isset($_GET['lang']) && (file_exists("installer/lang/" . $_GET['lang'] . ".php") || $_GET['lang'] == "en")) {
            $this->lang = $_GET['lang'];
            $_SESSION['lang'] = $this->lang;
        } else if (isset($_SESSION['lang']) && (file_exists("installer/lang/" . $_SESSION['lang'] . ".php") || $_SESSION['lang'] == "en")) {
            $this->lang = $_SESSION['lang'];
        } else {
            $this->lang = "en";
        }
        if ($this->lang !== "en") {
            $this->langTexts = include("installer/lang/" . $this->lang . ".php");
        }
    }

    /**
     * @throws Exception
     */
    private function handleRequest() {
        if (isset($_GET['action']) &&
                in_array($_GET['action'], $this->steps) &&
                method_exists($this, $_GET['action'])) {
            
        } else if (!isset($_GET['action']) || empty($_GET['action'])) {
            
        } else {
            throw new Exception("Invalid or not allowed action!");
        }
    }

    /**
     * 
     * @param String $input
     * @param array $replacements
     * @return String
     */
    public function translate($input, array $replacements = []) {
        if ($this->lang == "en" || !(isset($this->langTexts[$input]))) {
            $str = $input;
        } else {
            $str = $this->langTexts[$input];
        }

        if (!empty($replacements)) {
            return str_replace(array_keys($replacements), $replacements, $str);
        } else {
            return $str;
        }
    }

}

function __($string, array $replacements = []) {
    global $installer;
    echo $installer->translate($string, $replacements);
}

$installer = new Install();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php __("Install Cunity"); ?></title>
        <link href="../lib/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../lib/plugins/fontawesome/css/font-awesome.css" rel="stylesheet">
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            .breadcrumb>li+li:before {
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
                position:relative;
                height:643px;
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
                text-align:center;
                margin: 30px auto;
                font-style: italic;
                font-size: 0.9em;
                color:#999;
            }

            #steps > li.active > a {
                color: #777 !important;
                text-decoration: none;
                cursor:default;
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
                text-align:center;
                margin-top: 100px;
            }

            #splashscreen img.logo {
                width:320px;
                padding:15px;
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
                            <label><?php __("Please select your language for the installation-process"); ?></label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                                <select class="form-control" name="lang">
                                    <option value="en">English</option>
                                </select>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-lg btn-block" type="submit"><?php __("Start Installation"); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="container" id="installation-container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="page-header">
                            <h1><?php __("Install Cunity"); ?></h1>                        
                        </div>
                        <div id="installCarousel" class="carousel slide">
                            <ol class="breadcrumb" id="steps" role="tablist">
                                <li><a href="Install.php" title="<?php __("Back to language selection"); ?>"><i class="fa fa-globe"></i></a></li>
                                <li class="active"><a data-target="#installCarousel" href="#terms" data-slide-to="0"><?php __("Terms"); ?></a></li>
                                <li><a data-target="#installCarousel" href="#requirements" data-slide-to="1" ><?php __("Requirements"); ?></a></li>
                                <li><a data-target="#installCarousel" href="#database" data-slide-to="2"><?php __("Database"); ?></a></li>
                                <li><a data-target="#installCarousel" href="#settings" data-slide-to="3"><?php __("Settings"); ?></a></li>
                                <li><a data-target="#installCarousel" href="#account" data-slide-to="4"><?php __("Account"); ?></a></li>
                                <li><a data-target="#installCarousel" href="#finish" data-slide-to="5"><?php __("Finish"); ?></a></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active" id="terms">
                                    <span class="title"><?php __("Terms and Conditions"); ?></span>                                
                                    <div class="terms">
                                        <form>
                                            <div class="form-group">
                                                <label><?php __("Please agree to our Terms & Conditions first"); ?></label>
                                                <div class="form-control">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.   

                                                    Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.   

                                                    Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.   

                                                    Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.   

                                                    Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis.   

                                                    At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, At accusam aliquyam diam diam dolore dolores duo eirmod eos erat, et nonumy sed tempor et et invidunt justo labore Stet clita ea et gubergren, kasd magna no rebum. sanctus sea sed takimata ut vero voluptua. est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.   

                                                    Consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus.   

                                                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.   

                                                    Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.   

                                                    Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.   

                                                    Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo</div>                                            
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="1" name="accept-terms">
                                                    <?php __("I accept the Terms and Conditions"); ?>
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="item" id="requirements">
                                    <span class="title"><?php __("Check Requirements"); ?></span>
                                    <div class="list-group">                                        
                                        <li class="list-group-item"><i class="fa <?php if (version_compare(PHP_VERSION, '5.5.0', '>=')) { ?> fa-check-circle-o text-success <?php } else { ?>fa-times-circle-o text-danger <?php } ?> fa-fw fa-lg"></i>&nbsp;PHP Version 5.5+</li>
                                        <li class="list-group-item"><i class="fa fa-question text-muted fa-fw fa-lg"></i>&nbsp;MySQL Version 5+ (<?php __("Will be checkd in the next step"); ?>)</li>
                                        <li class="list-group-item">
                                            <i class="fa <?php if (class_exists("Zend_Version") && Zend_Version::compareVersion('1.12') < 1 && Zend_Version::compareVersion('2.0.0') == 1) { ?> fa-check-circle-o text-success <?php } else { ?>fa-times-circle-o text-danger <?php } ?> fa-fw fa-lg"></i>&nbsp;Zend Framework 1.12
                                            <?php if (!class_exists("Zend_Version") || true) { ?>
                                                <a href="#" class="pull-right"><i class="fa fa-download"></i>&nbsp;<?php __("Install"); ?></a>
                                            <?php } ?>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fa <?php if (class_exists("Smarty") && is_subclass_of("Smarty", "Smarty_Internal_TemplateBase")) { ?> fa-check-circle-o text-success <?php } else { ?>fa-times-circle-o text-danger <?php } ?> fa-fw fa-lg"></i>&nbsp;Smarty 3
                                            <?php if (!class_exists("Smarty") || true) { ?>
                                                <a href="#" class="pull-right"><i class="fa fa-download"></i>&nbsp;<?php __("Install"); ?></a>
                                            <?php } ?>
                                        </li>                                        
                                    </div>
                                </div>                                
                                <div class="item" id="database">
                                    <span class="title"><?php __("Setup Database"); ?></span>
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <form>
                                                <div class="form-group">
                                                    <label for="db-host"><?php __("Database-Host"); ?></label>
                                                    <input type="text" id="db-host" class="form-control" value="localhost" autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                    <label for="db-user"><?php __("Database-User"); ?></label>
                                                    <input type="text" id="db-user" class="form-control" autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                    <label for="db-password"><?php __("Database-Password"); ?></label>
                                                    <input type="password" id="db-password" class="form-control" autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                    <label for="db-name"><?php __("Database-Name"); ?></label>
                                                    <input type="text" id="db-name" class="form-control" autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                    <label for="db-prefix"><?php __("Database-Prefix"); ?></label>
                                                    <input type="text" id="db-prefix" class="form-control" value="cunity" autocomplete="off">
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-block"><i class="fa-check fa"></i>&nbsp;<?php __("Check Connection & install Cunity into database"); ?></button>
                                                </div>
                                            </form>  
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="item" id="settings">
                                    <span class="title"><?php __("Enter Cunity-Settings"); ?></span>
                                    <h4 class="page-header"><?php __("General Settings"); ?></h4>
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="sitename"><?php __("Name of your Cunity"); ?></label>
                                            <div class="col-lg-7">
                                                <input type="text" name="settings_core.sitename" id="sitename" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="siteurl"><?php __("URL of your Cunity"); ?></label>
                                            <div class="col-lg-7">
                                                <input type="text" name="settings_core.siteurl" id="siteurl" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="description"><?php __("Description"); ?></label>
                                            <div class="col-lg-7">
                                                <textarea class="form-control" id="description" name="settings_core.description"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label" for="contactmail"><?php __("Contact Mail"); ?></label>
                                            <div class="col-lg-7">
                                                <input type="text" name="settings_core.contact_mail" id="contactmail" class="form-control">
                                            </div>
                                        </div>
                                    </form>
                                    <h4 class="page-header"><?php __("Mail Settings"); ?></h4>
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label for="use-smtp" class="col-lg-3 control-label"><?php __("Mailserver"); ?></label>
                                            <div class="col-lg-7">
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" id="connection-type-smtp" required name="config[mail][smtp]"                                                                   
                                                               class="change-connection-type">&nbsp;<?php __("Use SMTP"); ?>
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input type="radio" id="connection-type-sendmail" required name="config[mail][smtp]"
                                                               class="change-connection-type">&nbsp;<?php __("Use PHP Sendmail"); ?>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="smtp-settings">
                                            <div class="form-group">
                                                <label for="smtp-host" class="col-lg-3 control-label"><?php __("SMTP-Host"); ?></label>

                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control"
                                                           id="smtp-host" name="config[mail][params][host]" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="smtp-port" class="col-lg-3 control-label"><?php __("SMTP-Port"); ?></label>

                                                <div class="col-lg-7">
                                                    <input type="number" class="form-control"
                                                           id="smtp-port" name="config[mail][params][port]" required data-bv-greaterthan
                                                           data-bv-greaterthan-value="25">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="smtp-auth"
                                                       class="col-lg-3 control-label"><?php __("SMTP-Authentication"); ?></label>

                                                <div class="col-lg-7">
                                                    <select class="form-control" id="smtp-auth" name="config[mail][params][auth]" required>                                                            
                                                        <option value="login"><?php __("Yes"); ?></option>
                                                        <option value="plain"><?php __("No"); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="smtp-username"
                                                       class="col-lg-3 control-label"><?php __("SMTP-Username"); ?></label>

                                                <div class="col-lg-7">
                                                    <input type="text" required class="form-control"
                                                           id="smtp-username"
                                                           name="config[mail][params][username]">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="smtp-password"
                                                       class="col-lg-3 control-label"><?php __("SMTP-Password"); ?></label>

                                                <div class="col-lg-7">
                                                    <input type="password" required class="form-control"
                                                           id="smtp-password"
                                                           name="config[mail][params][password]">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="smtp-ssl" class="col-lg-3 control-label"><?php __("SMTP-Security"); ?></label>

                                                <div class="col-lg-7">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="config[mail][params][ssl]"
                                                                   value="ssl">&nbsp;<?php __("Use SSL"); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="item" id="account">
                                    <span class="title"><?php __("Create Admin-Account"); ?></span>
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label class="control-label col-lg-3" for="input-username"><?php __("Username"); ?></label>

                                            <div class="col-lg-7">
                                                <input type="text" autocomplete="off" required class="form-control" id="input-username"
                                                       placeholder="<?php __("Username"); ?>" name="username">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3" for="input-email"><?php __("E-Mail"); ?></label>

                                            <div class="col-lg-7">
                                                <input type="email" required class="form-control" id="input-email" placeholder="<?php __("E-Mail"); ?>"
                                                       name="email">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3" for="input-firstname"><?php __("Firstname"); ?></label>

                                            <div class="col-lg-7">
                                                <input type="text" autocomplete="off" required class="form-control" id="input-firstname"
                                                       placeholder="<?php __("Firstname"); ?>" name="firstname">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3" for="input-lastname"><?php __("Lastname"); ?></label>

                                            <div class="col-lg-7">
                                                <input type="text" autocomplete="off" required class="form-control" id="input-lastname"
                                                       placeholder="<?php __("Lastname"); ?>" name="lastname">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3" for="input-password"><?php __("Password"); ?></label>

                                            <div class="col-lg-7">
                                                <input type="password" autocomplete="off" required class="form-control" id="input-password"
                                                       placeholder="<?php __("Password"); ?>" name="password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3" for="input-password-repeat"><?php __("Repeat password"); ?></label>

                                            <div class="col-lg-7">
                                                <input type="password" autocomplete="off" required class="form-control" id="input-password-repeat"
                                                       placeholder="<?php __("Repeat password"); ?>" name="password_repeat">
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom:10px">
                                            <label class="control-label col-lg-3"><?php __("I am"); ?></label>
                                            <div class="col-lg-7">
                                                <select class="form-control" name="sex" required>
                                                    <option value=""><?php __("Select your gender"); ?></option>
                                                    <option value="f"><?php __("Female"); ?></option>
                                                    <option value="m"><?php __("Male"); ?></option>
                                                </select>
                                            </div>
                                        </div>                                        
                                    </form>
                                </div>
                                <div class="item" id="finish">
                                    <span class="title"><?php __("Finish Installation"); ?></span>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-lg-2 clearfix"><a role="button" href="#installCarousel" id="installPrevButton" data-slide="prev" class="btn btn-default pull-left hidden"><i class="fa fa-chevron-left"></i>&nbsp;<?php __("Prev"); ?></a></div>
                            <div class="col-lg-8">
                                <div class="progress">
                                    <div class="progress-bar" id="installation-progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                        <span class="progress-status">0</span>%
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 clearfix"><a role="button" disabled href="#installCarousel" id="installNextButton" data-slide="next" class="btn btn-primary pull-right"><?php __("Next"); ?>&nbsp;<i class="fa fa-chevron-right"></i></a></div>                                                
                            <div class="col-lg-2 clearfix"><a role="button" disabled href=".." id="installFinishButton" class="btn btn-success pull-right hidden"><i class="fa fa-check"></i>&nbsp;<?php __("Finish"); ?></a></div>                                                
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
    <script>
        var origShow = jQuery.fn.show, origHide = jQuery.fn.hide;
        jQuery.fn.show = function() {
            $(this).removeClass("hidden");
            return origShow.apply(this, arguments);
        };
        jQuery.fn.hide = function() {
            $(this).addClass("hidden");
            return origHide.apply(this, arguments);
        };
        $(document).ready(function() {
            $('#installCarousel').carousel({
                interval: false
            });

            $('#installCarousel').on('slide.bs.carousel', function(e) {
                console.log(e);
                var c = $(this).data('bs.carousel')
                index = $("#installCarousel .item").index($(e.relatedTarget))+1;
                //index = (e.direction === "left") ? c.getItemIndex(c.$element.find(".item.active")) + 1 : c.getItemIndex(c.$element.find(".item.active")) - 1;

                $("#steps > li.active").removeClass("active");
                $("#steps > li:eq(" + index + ")").addClass("active");
                console.log(index);
                var percentage = (100 / ($("#steps > li").length - 1)) * index;
                if (percentage == 100)
                    $("#installation-progress").addClass("progress-bar-success");
                else
                    $("#installation-progress").removeClass("progress-bar-success");
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
            });
        });
    </script>
</body>
</html>
<?php
ob_end_flush();
