<?php

namespace Events\Models\Generator;
use Core\Cunity;
use Zend_Db_Expr;

/**
 * Class EventsQuery
 * @package Events\Models\Generator
 */
class EventsQuery {

    /**
     * @param string $status
     * @return Zend_Db_Expr
     * @throws \Exception
     */
    public static function getEventsListQuery($status = "> 0") {
        return new Zend_Db_Expr(Cunity::get("db")->select()
                        ->from(Cunity::get("config")->db->params->table_prefix . "_events_guests", "eventid")
                        ->where("status" . $status)
                        ->where("userid=?", $_SESSION['user']->userid));
    }

}
