<?php

namespace Admin\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Version
 * @package Admin\Models\Db\Table
 */
class Versions extends Table {

    /**
     * @var string
     */
    protected $_name = 'versions';

    /**
     * @var string
     */
    protected $_primary = 'timestamp';

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

    public function getVersions() {
        $res = $this->fetchAll();
        if ($res !== NULL) {
            $versions = [];
            foreach($res AS $version) {
                $versions[] = $version->timestamp;
            }
            return $versions;
        }
        return false;
    }

}
