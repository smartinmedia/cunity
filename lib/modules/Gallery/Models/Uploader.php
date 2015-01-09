<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * 1. YOU MUST NOT CHANGE THE LICENSE FOR THE SOFTWARE OR ANY PARTS HEREOF! IT MUST REMAIN AGPL.
 * 2. YOU MUST NOT REMOVE THIS COPYRIGHT NOTES FROM ANY PARTS OF THIS SOFTWARE!
 * 3. NOTE THAT THIS SOFTWARE CONTAINS THIRD-PARTY-SOLUTIONS THAT MAY EVENTUALLY NOT FALL UNDER (A)GPL!
 * 4. PLEASE READ THE LICENSE OF THE CUNITY SOFTWARE CAREFULLY!
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program (under the folder LICENSE).
 * If not, see <http://www.gnu.org/licenses/>.
 *
 * If your software can interact with users remotely through a computer network,
 * you have to make sure that it provides a way for users to get its source.
 * For example, if your program is a web application, its interface could display
 * a "Source" link that leads users to an archive of the code. There are many ways
 * you could offer source, and different solutions will be better for different programs;
 * see section 13 of the GNU Affero General Public License for the specific requirements.
 *
 * #####################################################################################
 */

namespace Cunity\Gallery\Models;

use Cunity\Core\Cunity;
use Skoch\Filter\File\Crop;
use Skoch\Filter\File\Resize;

/**
 * Class Uploader
 * @package Cunity\Gallery\Models
 */
class Uploader
{
    /**
     * @param $message
     */
    private function sendResponse($message = '')
    {
        echo($message);
        exit;
    }

    /**
     * @param $filename
     * @return string
     * @throws \Exception
     */
    public function upload($filename)
    {

        if (empty($_FILES) || $_FILES['file']['error']) {
            $this->sendResponse('{"OK": 0, "info": "Failed to move uploaded file."}');
        }

        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];
        $filePath = "./$fileName";

        // Open temp file
        $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
        if ($out) {
            $this->moveTempFile($out);
        } else {
            $this->sendResponse('{"OK": 0, "info": "Failed to open output stream."}');
        }

        if ($chunks == 0 || $chunk == $chunks - 1) {
            return $this->edit($filename, $fileName, $filePath);
        } else {
            $this->sendResponse();
            return '';
        }
    }

    /**
     * @param $filename
     * @param $fileName
     * @param $filePath
     * @return string
     * @throws \Cunity\Core\Exception
     */
    public function edit($filename, $fileName, $filePath)
    {
        $settings = Cunity::get("settings");
        $config = Cunity::get("config");
        $fileinfo = pathinfo($fileName);
        $destinationFile = "../data/uploads/" . $settings->getSetting("core.filesdir") . "/" . $filename . "." . strtolower($fileinfo['extension']);
        $previewFile = "../data/uploads/" . $settings->getSetting("core.filesdir") . "/prev_" . $filename . "." . strtolower($fileinfo['extension']);

        rename("{$filePath}.part", $destinationFile);
        copy($destinationFile, $previewFile);

        $this->resize($config, $destinationFile, $previewFile);
        return $filename . "." . strtolower($fileinfo['extension']);
    }

    /**
     * @param \Zend_Config $config
     * @param $destinationFile
     * @param $previewFile
     * @throws \Cunity\Core\Exception
     */
    public function resize(\Zend_Config $config, $destinationFile, $previewFile)
    {
        $resizer = new Resize($config->images);
        $preview = new Resize($config->previewImages);
        $crop = new Crop([
            "thumbwidth" => "thumbnail",
            "directory" => "../data/uploads/" . Cunity::get("settings")->getSetting("core.filesdir"),
            "prefix" => "thumb_"
        ]);
        $resizer->filter($destinationFile);
        $preview->filter($previewFile);
        $crop->filter($destinationFile);
    }

    /**
     * @param $out
     */
    public function moveTempFile($out)
    {
// Read binary input stream and append it to temp file
        $tempFile = fopen($_FILES['file']['tmp_name'], "rb");

        if ($tempFile) {
            /** @noinspection PhpAssignmentInConditionInspection */
            while ($buff = fread($tempFile, 4096)) {
                fwrite($out, $buff);
            }
        } else {
            $this->sendResponse('{"OK": 0, "info": "Failed to open input stream."}');
        }

        fclose($tempFile);
        fclose($out);

        unlink($_FILES['file']['tmp_name']);
    }
}
