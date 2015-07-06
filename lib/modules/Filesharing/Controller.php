<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2015 Smart In Media GmbH & Co. KG                            ##
 * ## CUNITY(R) is a registered trademark of Dr. Martin R. Weihrauch                     ##
 * ##  http://www.cunity.net                                                             ##
 * ##                                                                                    ##
 * ########################################################################################.
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

namespace Cunity\Filesharing;

use Cunity\Core\Exceptions\NotAllowed;
use Cunity\Core\Helper\FileSizeHelper;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\ModuleController;
use Cunity\Core\Request\Get;
use Cunity\Core\Request\Post;
use Cunity\Core\Request\Session;
use Cunity\Core\View\Ajax\View;
use Cunity\Filesharing\Helper\AccessHelper;
use Cunity\Filesharing\Helper\UploadHelper;
use Cunity\Filesharing\Models\Db\Table\FileRights;
use Cunity\Filesharing\Models\Db\Table\Files;
use Cunity\Filesharing\View\Filesharing;
use Cunity\Friends\Models\Db\Table\Relationships;
use Cunity\Register\Models\Login;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Class Controller.
 */
class Controller extends ModuleController
{
    /**
     * @var Filesharing
     */
    protected $view;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        Login::loginRequired();
        $this->view = new Filesharing();
        parent::__construct();
    }

    /**
     *
     */
    public function overview()
    {
        $relations = new Relationships();
        $friends = $relations->getFullFriendList();
        $this->view->assign(['friends' => $friends]);
        $this->view->assign('max_filesize', ini_get('upload_max_filesize'));
        $this->view->assign(
            'upload_limit',
            FileSizeHelper::getMaxUploadSize()
        );
        $this->view->assign(
            'upload_limit_human_readable',
            FileSizeHelper::reverseCompute(FileSizeHelper::getMaxUploadSize())
        );

        $files = new Files();
        $numberOfOwnFiles = $files->listOwnFiles()->count();

        $this->view->assign('numberOfOwnFiles', $numberOfOwnFiles);
    }

    /**
     *
     */
    public function listfiles()
    {
        $files = new Files();
        $fileList = $files->listFiles()->toArray();

        $this->status = is_array($fileList);
        $this->addData('result', $fileList);
    }

    /**
     *
     */
    public function create()
    {
        $uploader = new UploadHelper();
        $uploader->setExtension(UploadHelper::generateExtension($_FILES['file']['name']));
        $uploader->setTempFile($_FILES['file']['tmp_name']);
        $this->status = $uploader->upload();
        $files = new Files();
        $data = [
            'user_id' => Session::get('user')->userid,
            'title' => Post::get('title'),
            'description' => Post::get('description'),
            'filename' => $_FILES['file']['name'],
            'filenameondisc' => $uploader->getDestinationFilename(),
            'filesize' => $_FILES['file']['size'],
        ];
        $fileId = $files->insert($data);

        $data = [
            'file_id' => $fileId,
        ];

        if (Post::get('allFriends', '') !== '') {
            $fileRights = new FileRights();
            $data['all_friends'] = 1;
            $fileRights->insert($data);
        } else {
            foreach (Post::get('friends') as $friend) {
                $fileRights = new FileRights();
                $data['user_id'] = $friend;
                $fileRights->insert($data);
            }
        }

        Url::redirectToModule('filesharing');
    }

    /**
     * @throws \Zend_Db_Table_Exception
     */
    public function download()
    {
        $fileId = Get::get('x');

        if (AccessHelper::canRead($fileId)) {
            $file = new Files();
            $fileId = $file->find($fileId)[0];
            $fileName = __DIR__.'/../../../data/uploads/files/'.$fileId->user_id.'/'.$fileId->filenameondisc;

            header('Cache-Control: public');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename='.$fileId->filename);
            header('Content-Transfer-Encoding: binary');

            // read the file from disk
            readfile($fileName);
            exit;
        } else {
            throw new FileNotFoundException();
        }
    }

    /**
     *
     */
    public function delete()
    {
        $fileId = Post::get('fileid');
        if (AccessHelper::canDelete($fileId)) {
            $file = new Files();
            $this->status = $file->removeFile($fileId);
        } else {
            throw new NotAllowed();
        }
    }
}
