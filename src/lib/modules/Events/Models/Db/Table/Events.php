<?php

namespace Events\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;
use Friends\Models\Generator\FriendQuery;

/**
 * Class Events
 * @package Events\Models\Db\Table
 */
class Events extends Table {

    /**
     * @var string
     */
    protected $_name = 'events';
    /**
     * @var string
     */
    protected $_primary = 'id';

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function addEvent(array $data) {
        return $this->insert($data);
    }

    /**
     * @param $eventid
     * @return bool
     */
    public function deleteEvent($eventid) {
        return (0 < $this->delete($this->getAdapter() > quoteInto("id=?", $eventid)));
    }

    /**
     * @param $eventid
     * @return mixed
     */
    public function getEventData($eventid) {
        $returnValue = NULL;
        $res = $this->getAdapter()->fetchRow($this->getAdapter()->select()
                        ->from(["e" => $this->_dbprefix . "events"], ["*"])
                        ->joinLeft(["g" => $this->_dbprefix . "events_guests"], "g.eventid=e.id AND g.userid=" . $this->getAdapter()->quote($_SESSION['user']->userid), ["guestid", "status"])
                        ->joinLeft(["u" => $this->_dbprefix . "users"], "e.userid = u.userid", ["username", "name"])
                        ->joinLeft(["i" => $this->_dbprefix . "gallery_images"], "i.id=e.imageId", ["filename"])
                        ->joinLeft(["gc" => $this->_dbprefix . "events_guests"], "g.eventid=e.id", new \Zend_Db_Expr("COUNT(gc.guestid) AS guestcount"))
                        ->group("gc.guestid")
                        ->where("e.id=?", $eventid));
        if ($res !== NULL){
            $returnValue = $res;
        }

        return $returnValue;
    }

    /**
     * @param $start
     * @param $end
     * @return array
     */
    public function fetchBetween($start, $end) {
        $query = $this->getAdapter()->select()->from(["e" => $this->_dbprefix . "events"], ["*", new \Zend_Db_Expr("UNIX_TIMESTAMP(start)*1000 AS start"), new \Zend_Db_Expr("UNIX_TIMESTAMP(start)*1000 AS end")])
                ->joinLeft(["g" => $this->_dbprefix . "events_guests"], "g.eventid=e.id AND g.userid=" . $this->getAdapter()->quote($_SESSION['user']->userid), ["guestid", "status"])
                ->joinLeft(["u" => $this->_dbprefix . "users"], "e.userid = u.userid", ["username", "name"])
                ->joinLeft(["pi" => $this->_dbprefix . "gallery_images"], "pi.id = u.profileImage AND e.type = 'birthday'", "filename AS pimg")
                ->where("e.start BETWEEN " . $this->getAdapter()->quote($start) . " AND " . $this->getAdapter()->quote($end))
                ->where("e.type = 'event' OR (e.type = 'birthday' AND e.userid IN (" . FriendQuery::getFriendListQuery("=2") . "))")
                ->where("(g.guestid IS NULL AND e.type = 'birthday') OR (g.guestid IS NOT NULL AND e.type = 'event')");
        return $this->getAdapter()->fetchAll($query);
    }

    /**
     * @param $eventid
     * @param $values
     * @return bool
     */
    public function updateEvent($eventid, $values) {
        return (0 < $this->update($values, $this->getAdapter()->quoteInto("id=?", $eventid)));
    }

} 
