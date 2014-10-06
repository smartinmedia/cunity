<?php

/**
 * Zend Framework addition by skoch
 *
 * @category   Skoch
 * @package    Skoch_Filter
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author     Stefan Koch <cct@stefan-koch.name>
 */
namespace Skoch\Filter\File\Adapter;

/**
 * Resizes a given file and saves the created file
 *
 * @category   Skoch
 * @package    Skoch_Filter
 */
abstract class AbstractAdapter
{
    /**
     * @param $width
     * @param $height
     * @param $keepRatio
     * @param $file
     * @param $target
     * @param bool $keepSmaller
     * @return mixed
     */
    abstract public function resize($width,
                                    $height,
                                    $keepRatio,
                                    $file,
                                    $target,
                                    $keepSmaller = true);

    /**
     * @param $oldWidth
     * @param $oldHeight
     * @param $width
     * @param $height
     * @return array
     */
    protected function _calculateWidth($oldWidth, $oldHeight, $width, $height)
    {
        // now we need the resize factor
        // use the bigger one of both and apply them on both
        $factor = max(($oldWidth / $width), ($oldHeight / $height));
        return [$oldWidth / $factor, $oldHeight / $factor];
    }
}
