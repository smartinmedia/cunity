<?php

use Behat\Behat\Context\Step\Then;
use Behat\Behat\Context\Step\When;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Features context.
 */
class FeatureContext extends RawMinkContext
{
    /**
     * @var string
     */
    const SPECiAL_CHARS = 'äöüÄÖÜß!\"§$%&/()=?´`<>|\'#+*~,.-;:_@\\{[]}';

    /**
     * @var int
     */
    private static $_timestamp = 0;

    /**
     * @var string
     */
    private static $_testString = '';

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters
     * @return \FeatureContext
     */
    public function __construct(array $parameters)
    {
        $this->useContext('mink', new MinkContext($parameters));
        $this->useContext('preparation', new PreparationContext($parameters));
        $this->useContext('navigate', new NavigateContext($parameters));
        $this->useContext('user', New UserContext($parameters));
        $this->useContext('wall', new WallContext($parameters));
        $this->useContext('error', new ErrorContext($parameters));

        self::$_timestamp = time();

        if (self::$_timestamp == 0) {
            self::$_testString = 'test' . self::SPECiAL_CHARS . self::$_timestamp;
        }
    }

    /**
     * @Given /^I fill in "([^"]*)" with testStringWithSpecialCharacters$/
     */
    public function iFillInWithTeststringwithspecialcharacters($field)
    {
        return [
            new When(
                'I fill in "'
                . $field
                . '" with "test'
                . self::SPECiAL_CHARS . self::$_timestamp . '"'
            )
        ];
    }

    /**
     * @Then /^I should see testStringWithSpecialCharacters$/
     */
    public function iShouldSeeTeststringwithspecialcharacters()
    {
        return new Then(
            'I should see "' . self::$_testString . '"'
        );
    }

    /**
     * @Given /^I wait$/
     */
    public function iWait()
    {
        sleep(1);
    }
}