<?php

namespace Admin\Models;

use \Core\Cunity;
use Core\View\Ajax\View;

/**
 * Class Process
 * @package Admin\Models
 */
class Process {

    /**
     * @var array
     */
    private $validForms = ["config", "settings", "mailtemplates"];

    /**
     * @param $form
     */
    public function __construct($form) {
        if (in_array($form, $this->validForms)) {
            $this->save($form);
        }
    }

    /**
     * @param $form
     * @throws \Exception
     * @throws \Zend_Config_Exception
     */
    private function save($form) {

        $res = [];
        switch ($form) {
            case "settings":
                foreach ($_POST AS $key => $value) {
                    if (strpos($key, "settings-") !== false) {
                        $setting = explode("-", $key);
                        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
                        $settings = \Core\Cunity::get("settings");
                        $res[] = $settings->setSetting(preg_replace('/_/', '.', $setting[1], 1), $value);
                    }
                }
                break;
            case "config":
                $config = new \Zend_Config_Xml("../data/config.xml");
                $configWriter = new \Zend_Config_Writer_Xml(["config" => new \Zend_Config(self::array_merge_recursive_distinct($config->toArray(), $_POST['config'])), "filename" => "../data/config.xml"]);
                $configWriter->write();
                break;
            case "mailtemplates":
                $settings = Cunity::get("settings");
                $res[] = $settings->setSetting("core.mail_header", $_POST['mail_header']);
                $res[] = $settings->setSetting("core.mail_footer", $_POST['mail_footer']);

                break;
        }
        $view = new View(!in_array(false, $res));
        $view->addData(["panel" => $_POST['panel']]);
        $view->sendResponse();
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     * @author Daniel <daniel (at) danielsmedegaardbuus (dot) dk>
     * @author Gabriel Sobrinho <gabriel (dot) sobrinho (at) gmail (dot) com>
     */
    public static function array_merge_recursive_distinct(array $array1, array $array2) {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
                $merged [$key] = self::array_merge_recursive_distinct($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }

        return $merged;
    }

}
