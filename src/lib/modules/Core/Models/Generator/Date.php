<?php

namespace Core\Models\Generator;

/**
 * Class Date
 * @package Core\Models\Generator
 */
class Date {

    /**
     * @param $since
     * @return string
     */
    public static function time_since($since) {
        $chunks = [
            [22896000, 'year'],
            [2592000, 'month'],
            [604800, 'week'],
            [86400, 'day'],
            [3600, 'hour'],
            [60, 'minute'],
            [1, 'second']
        ];

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
        return $print;
    }

}
