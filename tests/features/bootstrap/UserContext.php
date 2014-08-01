<?php

use Behat\Behat\Context\Step\Given;
use Behat\Behat\Context\Step\When;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Class UserContext
 */
class UserContext extends RawMinkContext
{
    /**
     * @var string
     */
    const USER = 'o.monneke@googlemail.com';

    /**
     * @var string
     */
    const PASSWORD = '1234567890';

    /**
     * @var string
     */
    const TESTUSER_PREFIX = 'testUser';

    /**
     * @var string
     */
    const TESTUSER_EMAIL_PREFIX = 'testUser';

    /**
     * @var string
     */
    const TESTUSER_EMAIL_DOMAIN = 'coersquad.de';

    /**
     * @When /^I fill in "([^"]*)" with testuserWithSpecialChars$/
     */
    public function iFillInWithTestuserwithspecialchars($field)
    {
        return new When(
            'I fill in "'
            .$field
            .'" with "'
            .self::TESTUSER_PREFIX
            .FeatureContext::SPECiAL_CHARS
            .time()
            .'"'
        );
    }

    /**
     * @Given /^I fill in "([^"]*)" with testuserEmail$/
     */
    public function iFillInWithTestuseremail($field)
    {
        return new When(
            'I fill in "'
            .$field
            .'" with "'
            .self::TESTUSER_EMAIL_PREFIX
            .time()
            .'@'
            .self::TESTUSER_EMAIL_DOMAIN
            .'"'
        );
    }

    /**
     * @When /^I am oliver$/
     */
    public function iAmOliver()
    {
        return [
            new Given('I am on "/"'),
            new When('I fill in "email" with "'.self::USER.'"'),
            new When('I fill in "password" with "'.self::PASSWORD.'"'),
            new When('I press "Log in"')
        ];
    }
}