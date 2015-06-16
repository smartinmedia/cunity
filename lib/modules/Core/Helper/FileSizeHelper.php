<?php

namespace Cunity\Core\Helper;

use Cunity\Core\Cunity;

/**
 * Class FileSizeHelper.
 */
class FileSizeHelper
{
    /**
     * @var array
     */
    private static $units = [
        0 => 'b',
        1 => 'kb',
        2 => 'mb',
        3 => 'gb',
        4 => 'tb',
    ];

    /**
     * @param $value
     *
     * @return int
     */
    public static function compute($value)
    {
        if (preg_match('/^[0-9]+$/', $value)) {
            return intval($value);
        }

        preg_match('/([a-zA-Z]+)/', $value, $matches);

        $unit = strtolower($matches[1]);
        $result = intval($value);

        switch ($unit) {
            case 'z':
            case 'zb':
                $result = self::multiply($result);
            case 't':
            case 'tb':
                $result = self::multiply($result);
            case 'g':
            case 'gb':
                $result = self::multiply($result);
            case 'm':
            case 'mb':
                $result = self::multiply($result);
            case 'kb':
                $result = self::multiply($result);
            default:
                break;
        }

        return intval($result);
    }

    /**
     * @param $value
     *
     * @return float
     */
    public static function reverseCompute($value)
    {
        $counter = 0;

        while (intval($value) >= 1000) {
            $value = self::divide($value);
            $counter++;
        }

        return $value.' '.strtoupper(self::$units[$counter]);
    }

    /**
     * @param $value
     *
     * @return float
     */
    private static function divide($value)
    {
        return $value / 1000;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    private static function multiply($value)
    {
        return $value * 1000;
    }

    /**
     * @return string
     *
     * @throws \Cunity\Core\Exceptions\InstanceNotFound
     */
    public static function getMaxUploadSize()
    {
        $setting = [];

        $setting[] = self::compute(ini_get('upload_max_filesize'));
        $setting[] = self::compute(Cunity::get('config')->site->upload_limit);
        $setting[] = self::compute(ini_get('post_max_size'));

        sort($setting);

        return $setting[0];
    }
}
