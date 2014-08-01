<?php
namespace Core\Models;

/**
 * Class Request
 * @package Core\Models
 */
class Request {

    /**
     * @return bool
     */
    public static function isAjaxRequest() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

}
