<?php

namespace Contact\Models;

use Register\Models\Login;
use Contact\Models\Db\Table\Contact;
use Contact\View\ContactMail;
use \Core\View\Message;

/**
 * Class ContactForm
 * @package Contact\Models
 */
class ContactForm {

    /**
     *
     */
    public function __construct() {
        $this->handleInput();
    }

    /**
     *
     */
    private function handleInput() {
        if (isset($_POST['message'])) {
            $contactDb = new Contact();
            $res = $contactDb->insert([
                "userid" => (Login::loggedIn()) ? $_SESSION['user']->userid : 0,
                "firstname" => $_POST['firstname'],
                "lastname" => $_POST['lastname'],
                "email" => $_POST['email'],
                "subject" => $_POST['subject'],
                "message" => $_POST['message'],
            ]);
            if ($res) {
                $cc = (isset($_POST['send_copy'])&&$_POST['send_copy']==1) ? ["email"=>$_POST['email'],"name"=>$_POST['firstname']." ".$_POST['lastname']] : [];                
                new ContactMail([],["subject"=>$_POST['subject'],"message"=>$_POST['message']],$cc);
                new Message("Finished!", "Your Message was sent successfully!", "success");
            } else
                new Message("Sorry!", "There was an error in our system, please try again later", "danger");
        }
    }

}
