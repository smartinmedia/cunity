<?php

namespace Cunity\Filesharing\Models\Db\Table;

use Cunity\Core\Models\Db\Abstractables\Table;

/**
 * Class FileRights.
 */
class FileRights extends Table
{
    /**
     * @var string
     */
    protected $_name = 'filesharing_rights';

    /**
     * @var string
     */
    protected $_primary = 'id';
}
