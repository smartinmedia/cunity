<?php

namespace Friends\Models\Generator;
use Core\Cunity;
use Zend_Db_Expr;

/**
 * Class FriendQuery
 * @package Friends\Models\Generator
 */
class FriendQuery {

    /**
     * @param string $status
     * @return Zend_Db_Expr
     * @throws \Exception
     */
    public static function getFriendListQuery($status = "> 0") {
        return new Zend_Db_Expr(Cunity::get("db")->select()
        ->from(Cunity::get("config")->db->params->table_prefix . "_relations", new Zend_Db_Expr("(CASE WHEN sender = " . $_SESSION['user']->userid . " THEN receiver WHEN receiver = " . $_SESSION['user']->userid . " THEN sender END)"))
        ->where("status".$status)
        ->where("sender=? OR receiver=?", $_SESSION['user']->userid));
    }

}
