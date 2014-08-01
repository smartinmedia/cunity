<?php
namespace Core;

/**
 * Class Exception
 * @package Core
 */
class Exception extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}


