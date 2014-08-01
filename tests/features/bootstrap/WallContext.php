<?php

use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Class WallContext
 */
class WallContext extends RawMinkContext
{
    /**
     * @When /^I remove wall entries$/
     */
    public function iRemoveWallEntries()
    {
        $returnValue = $this->getSession()->evaluateScript("jQuery.each($('.options'), function(index, value) {  });");
    }
}