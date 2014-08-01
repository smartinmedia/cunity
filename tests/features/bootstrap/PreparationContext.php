<?php

use Behat\Behat\Event\ScenarioEvent;
use Behat\Behat\Event\StepEvent;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Class PreparationContext
 */
class PreparationContext extends RawMinkContext
{
    /**
     *
     */
    public function loginAsOliver()
    {
        $this->getSession()->visit($this->locatePath('/'));
        $element = $this->getSession()->getPage();
        $element->fillField('email', UserContext::USER);
        $element->fillField('password', UserContext::PASSWORD);
        $submit = $element->findButton('Log in');
        $submit->click();
    }

    /**
     * @BeforeScenario
     */
    public function maximizeWindow(ScenarioEvent $event)
    {
        /** @var $driver Selenium2Driver */
#        $driver = $this->getSession()->getDriver();
#
#        if ($driver instanceof Selenium2Driver)
#        {
#            $this->getSession()->resizeWindow(1480, 850);
#        }
#
#        $this->loginAsOliver();
    }
}