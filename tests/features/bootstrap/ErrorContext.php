<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Class ErrorContext
 */
class ErrorContext extends RawMinkContext
{

    /**
     * Take screenshot when step fails.
     * Works only with Selenium2Driver.
     *
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        /** @var $driver Selenium2Driver */
        $driver = $this->getSession()->getDriver();

        if (($driver instanceof Selenium2Driver) && 4 === $event->getResult()) {
            $directory = 'C:/xampp/htdocs/cunity/tests/screenshots/'
                . $event->getLogicalParent()->getFeature()->getTitle()
                . '.'
                . $event->getLogicalParent()->getTitle();

            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $filename = sprintf(
                '%s_%s',
                $this->getMinkParameter('browser_name'),
                date('H-i-s_d-m-Y')
            );
            $file = $directory
                . '/'
                . str_replace('.', '_', $filename)
                . '.png';
            $handle = fopen($file, 'w');
            sleep(0.5);
            fwrite($handle, $driver->getScreenshot());
        }
    }
}