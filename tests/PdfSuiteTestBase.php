<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    4:17 PM
 **/

namespace NcJoes\PdfSuite\Tests;

use NcJoes\PdfSuite\PdfSuite;
use NcJoes\PdfSuite\Config;

abstract class PdfSuiteTestBase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var $suite  PdfSuite
     */
    protected $suite;

    /**
     * @var $info \NcJoes\PdfSuite\Utils\PdfInfo
     */
    protected $info;

    public function setUp()
    {
        parent::setUp();
        Config::setBinDirectory(__DIR__.'/../vendor/bin/poppler');
        Config::setOutputDirectory(__DIR__.'/results', true);
        Config::doCleanupOnExit(false);

        $file = dirname(__FILE__).'\sources\test1.pdf';
        $this->suite = new PdfSuite($file);
        $this->info = $this->suite->getPdfInfo();
    }
}