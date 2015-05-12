<?php

/**
 * ########################################################################################
 * ## CUNITY(R) V2.0 - An open source social network / "your private social network"     ##
 * ########################################################################################
 * ##  Copyright (C) 2011 - 2014 Smart In Media GmbH & Co. KG                            ##
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

namespace Cunity\Events\Models;

use Cunity\Core\Cunity;
use Cunity\Core\Exceptions\EventNotFound;
use Cunity\Core\Exceptions\NotAllowed;
use Cunity\Core\Exceptions\PageNotFound;
use Cunity\Core\Models\Generator\Url;
use Cunity\Core\Request\Session;
use Cunity\Core\View\Ajax\View;
use Cunity\Core\View\Message;
use Cunity\Events\Models\Db\Table\Events;
use Cunity\Events\Models\Db\Table\Guests;
use Cunity\Events\View\Event;
use Cunity\Events\View\EventCrop;
use Cunity\Events\View\EventEdit;
use Cunity\Gallery\Models\Db\Table\GalleryAlbums;
use Cunity\Gallery\Models\Db\Table\GalleryImages;
use Cunity\Newsfeed\Models\Db\Table\Walls;
use DateTime;
use Skoch\Filter\File\Crop;

/**
 * Class Process.
 */
class Process
{
    /**
     * @param $action
     */
    public function __construct($action)
    {
        if (method_exists($this, $action)) {
            call_user_func([$this, $action]);
        }
    }

    /**
     *
     */
    private function createEvent()
    {
        $events = new Events();
        $result = false;
        $res = $events->addEvent([
            'userid' => Session::get('user')->userid,
            'title' => Post::get('title'),
            'description' => Post::get('description'),
            'place' => Post::get('place'),
            'start' => Post::get('start'),
            'imageId' => 0,
            'type' => 'event',
            'privacy' => Post::get('privacy'),
            'guest_invitation' => (Post::get('guest_invitation') !== null) ? 1 : 0,
        ]);
        if ($res > 0) {
            $guests = new Guests();
            $walls = new Walls();
            $gallery_albums = new GalleryAlbums();
            $guests->addGuests($res, Session::get('user')->userid, 2, false);
            $result = $walls->createWall($res, 'event') && $gallery_albums->insert([
                    'title' => '',
                    'description' => '',
                    'owner_id' => $res,
                    'owner_type' => 'event',
                    'type' => 'event',
                    'user_upload' => 0,
                    'privacy' => 2,
                ]);
        }

        $view = new View($result);
        if ($result) {
            $view->addData($events->getEventData($res));
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadEvents()
    {
        $start = date('Y-m-d H:i:s', (Get::get('from') / 1000));
        $end = date('Y-m-d H:i:s', (Get::get('to') / 1000));

        $events = new Events();
        $result = $events->fetchBetween($start, $end);
        $view = new View($result !== null);
        if (($result !== null)) {
            $view->addData([
                'success' => 1,
                'result' => $result,
            ]);
        } else {
            $view->addData([
                'success' => 0,
                'result' => $result,
            ]);
        }
        $view->sendResponse();
    }

    /**
     *
     */
    private function loadEvent()
    {
        $events = new Events();
        if (Get::get('x') == 'edit') {
            $eventData = $events->getEventData(intval(Get::get('action')));
            if ($eventData['userid'] !== Session::get('user')->userid) {
                throw new NotAllowed();
            }
            $view = new EventEdit();
            $eventData['date'] = new DateTime($eventData['start']);
            $view->assign('event', $eventData);
            $view->show();
        } else {
            $guests = new Guests();
            $id = explode('-', Get::get('action'));
            $view = new Event();
            $data = $events->getEventData($id[0]);
            if ($data === null || $data === false) {
                throw new EventNotFound();
            }
            $data['date'] = new DateTime($data['start']);
            $data['guests'] = $guests->getGuests($id[0]);
            $view->assign('event', $data);
            $view->show();
        }
    }

    /**
     *
     */
    private function changeStatus()
    {
        if (Post::get('eventid') !== null && Post::get('status') !== null) {
            $guests = new Guests();
            $res = $guests->changeStatus(Post::get('status'), Session::get('user')->userid, Post::get('eventid'));
            $view = new View($res !== false);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function invite()
    {
        if (Post::get('receiver') !== null && Post::get('receiver') !== '') {
            $conv = new Guests();
            $result = $conv->addGuests(Post::get('eventid'), Post::get('receiver'), 1, true);
            $view = new \Cunity\Core\View\Ajax\View($result);
            $view->sendResponse();
        }
    }

    /**
     *
     */
    private function loadGuestList()
    {
        $guests = new Guests();
        $g = $guests->getGuests(Post::get('eventid'));
        $view = new View(is_array($g));
        $view->addData(['guests' => $g]);
        $view->sendResponse();
    }

    /**
     *
     */
    private function edit()
    {
        $view = new View();
        if (Post::get('edit') !== 'editPhoto') {
            $events = new Events();
            $events->updateEvent(intval(Get::get('x')), array_intersect_key(Post::get(), array_flip(['title', 'description', 'place', 'date', 'time', 'privacy', 'guest_invitation'])));
            $view->addData(['msg' => 'Successfully saved']);
            $view->sendResponse();
        } elseif (Post::get('edit') === 'editPhoto') {
            $gimg = new GalleryImages();
            $result = $gimg->uploadEventImage(Post::get('eventid'));
            if ($result !== false) {
                $view->setStatus(true);
                $view->addData($result);
                $view->sendResponse();
            } else {
                new Message('Sorry!', 'Something went wrong on our server!');
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function cropImage()
    {
        if (Get::get('action') === null || Get::get('x') === '') {
            throw new PageNotFound();
        }
        $imageid = Get::get('x');
        $eventid = Get::get('y');
        $images = new GalleryImages();
        $events = new Events();
        $eventData = $events->getEventData($eventid);
        $result = $images->getImageData($imageid);
        if ($eventData['userid'] == Session::get('user')->userid) {
            $view = new EventCrop();
            $eventData['date'] = new DateTime($data['start']);
            $view->assign(['event' => $eventData, 'result' => $result[0], 'type' => Get::get('y'), 'image' => getimagesize('../data/uploads/'.Cunity::get('settings')->getSetting('core.filesdir').'/'.$result[0]['filename'])]);
            $view->show();
        } else {
            throw new NotAllowed();
        }
    }

    /**
     * @throws \Exception
     */
    private function crop()
    {
        $file = new Crop([
            'x' => Post::get('crop-x'),
            'y' => Post::get('crop-y'),
            'x1' => Post::get('crop-x1'),
            'y1' => Post::get('crop-y1'),
            'thumbwidth' => 970,
            'directory' => '../data/uploads/'.Cunity::get('settings')->getSetting('core.filesdir'),
            'prefix' => 'cr_',
        ]);
        $file->filter(Post::get('crop-image'));
        $events = new Events();
        if ($events->updateEvent(Post::get('eventid'), ['imageId' => Post::get('imageid')])) {
            header('Location: '.Url::convertUrl('index.php?m=events&action='.Post::get('eventid')));
        }
    }
}
