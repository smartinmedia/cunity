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
 * Resizes a given file with the gd adapter and saves the created file
 *
 * @category   Skoch
 * @package    Skoch_Filter
 */
class Gd extends
    AbstractAdapter
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
    public function resize($width,
                           $height,
                           $keepRatio,
                           $file,
                           $target,
                           $keepSmaller = true)
    {
        list($oldWidth, $oldHeight, $type) = getimagesize($file);

        $source = $this->getType($file, $type);
        list($width, $height) = $this->calculateMetrics($width, $height, $keepRatio, $keepSmaller, $oldWidth, $oldHeight);
        $thumb = imagecreatetruecolor($width, $height);
        $this->createThumbnail($width, $height, $target, $thumb, $source, $oldWidth, $oldHeight);

        return $target;
    }

    /**
     * @param $file
     * @param $target
     * @param $thumbwidth
     * @return mixed
     */
    public function thumbnail($file, $target, $thumbwidth)
    {
        list($width, $height, $type) = getimagesize($file);

        $source = false;

        switch ($type) {
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($file);
                break;
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($file);
                break;
        }
        $x = $width / 4;
        $y = $height / 4;
        $w = $width / 2;
        $h = $w;
        $thumb = imagecreatetruecolor($thumbwidth, $thumbwidth);

        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);

        imagecopyresampled(
            $thumb,
            $source,
            0,
            0,
            $x,
            $y,
            $thumbwidth,
            $thumbwidth,
            $w,
            $h
        );

        imagejpeg($thumb, $target);

        return $file;
    }

    /**
     * @param $x
     * @param $y
     * @param $x1
     * @param $y1
     * @param $file
     * @param $target
     * @param $thumbwidth
     * @return mixed
     */
    public function crop($x, $y, $x1, $y1, $file, $target, $thumbwidth)
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        list(, , $type) = getimagesize($file);

        $source = $this->getType($file, $type);

        $w = abs($x1 - $x);
        $h = abs($y1 - $y);
        $destinationRatio = $w / $h;
        $thumbheight = $thumbwidth / $destinationRatio;
        $thumb = imagecreatetruecolor($h, $w);
        $this->createThumbnail($w, $h, $target, $thumb, $source, $thumbwidth, $thumbheight);

        return $file;
    }

    /**
     * @param $width
     * @param $height
     * @param $target
     * @param $thumb
     * @param $source
     * @param $oldWidth
     * @param $oldHeight
     */
    private function createThumbnail($width, $height, $target, $thumb, $source, $oldWidth, $oldHeight)
    {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);

        imagecopyresampled(
            $thumb,
            $source,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $oldWidth,
            $oldHeight
        );

        imagecopyresampled(
            $thumb,
            $source,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $oldWidth,
            $oldHeight
        );
        imagejpeg($thumb, $target);
    }

    /**
     * @param $file
     * @param $type
     * @return resource
     */
    private function getType($file, $type)
    {
        $source = false;
        switch ($type) {
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($file);
                break;
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($file);
                break;
        }
        return $source;
    }

    /**
     * @param $width
     * @param $height
     * @param $keepRatio
     * @param $keepSmaller
     * @param $oldWidth
     * @param $oldHeight
     * @return array
     */
    private function calculateMetrics($width, $height, $keepRatio, $keepSmaller, $oldWidth, $oldHeight)
    {
        if (!$keepSmaller || $oldWidth > $width || $oldHeight > $height) {
            if ($keepRatio) {
                list($width, $height) = $this->calculateWidth(
                    $oldWidth,
                    $oldHeight,
                    $width,
                    $height
                );
                return array($width, $height);
            }
            return array($width, $height);
        } else {
            $width = $oldWidth;
            $height = $oldHeight;
            return array($width, $height);
        }
    }

}
