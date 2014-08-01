<?php

namespace Contact\Models\Db\Table;

use Core\Models\Db\Abstractables\Table;

/**
 * Class Contact
 * @package Contact\Models\Db\Table
 */
class Contact extends Table {

    /**
     * @var string
     */
    protected $_name = 'contact';
    /**
     * @var string
     */
    protected $_primary = 'contact_id';

    /**
     *
     */
    public function __construct() {
        parent::__construct();
    }

}
