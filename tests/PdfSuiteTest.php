<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    4:17 PM
 **/

use NcJoes\PdfSuite\PdfSuite;
use NcJoes\PdfSuite\Config;

class PdfSuiteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var $suite  PdfSuite
     */
    private $suite;

    public function setUp()
    {
        parent::setUp();
        Config::setBinDirectory(__DIR__.'/../vendor/bin/poppler');
        Config::setOutputDirectory(__DIR__.'/results', true);
        Config::doCleanupOnExit(false);

        $file = dirname(__FILE__).'\sources\test1.pdf';
        $this->suite = new PdfSuite($file);
    }

    public function testGetInfo()
    {
        var_dump($this->suite->getPdfInfo()->getInfo());
    }

    public function testPdfToHtmlConverter()
    {
        var_dump($this->suite->getPdfToHtmlConverter()->useDefaultSettings()->convert());
    }


    public function testPdfToJpegConverter()
    {
        var_dump($this->suite->getPdfToJpegConverter()->convert());
    }

    public function testPdfToPngConverter()
    {
        var_dump($this->suite->getPdfToPngConverter()->convert());
    }

    public function testPdfToPsConverter()
    {
        var_dump($this->suite->getPdfToPsConverter()->convert());
    }

    public function testPdfToSvgConverter()
    {
        var_dump($this->suite->getPdfToSvgConverter()->convert());
    }

}