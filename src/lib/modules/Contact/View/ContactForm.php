<?php

namespace Contact\View;

use Core\View\View;
use Register\Models\Login;

/**
 * Class ContactForm
 * @package Contact\View
 */
class ContactForm extends View
{

    /**
     * @var string
     */
    protected $_templateDir = "contact";
    /**
     * @var string
     */
    protected $_templateFile = "contactform.tpl";
    /**
     * @var array
     */
    protected $_metadata = ["title" => "Contact Form"];

    /**
     * @throws \Core\Exception
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        if (Login::loggedIn()) {
            $user = $_SESSION['user'];
            $userData = [
                "firstname" => $user->firstname,
                "lastname" => $user->lastname,
                "email" => $user->email
            ];
        } else
            $userData = ["firstname" => "", "lastname" => "", "email" => ""];
        $this->registerScript("contact", "contactform");
        $this->assign("userData", $userData);
        $this->show();
    }

    /**
     *
     */
    public function render()
    {

    }

}
