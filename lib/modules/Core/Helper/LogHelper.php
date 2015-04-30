<?php

namespace Cunity\Core\Helper;

use Cunity\Core\Models\Db\Table\Log;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class LogHelper.
 */
class LogHelper implements LoggerInterface
{
    protected $level;

    protected $message;

    protected $context;

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function emergency($message, array $context = [])
    {
        $this->level = LogLevel::EMERGENCY;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function alert($message, array $context = [])
    {
        $this->level = LogLevel::ALERT;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function critical($message, array $context = [])
    {
        $this->level = LogLevel::CRITICAL;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function error($message, array $context = [])
    {
        $this->level = LogLevel::ERROR;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function warning($message, array $context = [])
    {
        $this->level = LogLevel::WARNING;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function notice($message, array $context = [])
    {
        $this->level = LogLevel::NOTICE;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function info($message, array $context = [])
    {
        $this->level = LogLevel::INFO;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function debug($message, array $context = [])
    {
        $this->level = LogLevel::DEBUG;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null|void
     */
    public function log($level, $message, array $context = [])
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
        $this->save();
    }

    /**
     *
     */
    protected function save()
    {
        $log = new Log();
        $userid = $_SESSION['user']->userid;

        if ($userid === null) {
            $userid = 0;
        }

        $log->insert(['level' => $this->level, 'message' => $this->message, 'context' => json_encode($this->context), 'user_id' => $userid]);
    }
}
