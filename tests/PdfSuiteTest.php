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
    public function setUp()
    {
        parent::setUp();
        Config::setBinDirectory(__DIR__.'/../vendor/bin/poppler');
        Config::setOutputDirectory(__DIR__.'/results/test-'.date('m-d-Y_H-i-s'), true);
    }

    public function testGetInfo()
    {
        $file = __DIR__.'/sources/test1.pdf';
        $pdf_suite = new PdfSuite($file);
        $pdf_info = $pdf_suite->getPdfInfo();

        $this->assertArrayHasKey('pages', $pdf_info->getInfo());
    }

    public function testPdfToJpegConverter()
    {
        $file = dirname(__FILE__).'\sources\test1.pdf';
        $pdf_suite = new PdfSuite($file);
        Config::setOutputDirectory(__DIR__.'/results/test-'.date('m-d-Y_H-i-s'), true);

        $pdf_suite->getPdfToJpegConverter()->convert();
    }

    public function testPdfToPngConverter()
    {
        $file = dirname(__FILE__).'\sources\test1.pdf';
        $pdf_suite = new PdfSuite($file);
        Config::setOutputDirectory(__DIR__.'/results/test-'.date('m-d-Y_H-i-s'), true);

        $pdf_suite->getPdfToPngConverter()->convert();
    }

    public function testPdfToPsConverter()
    {
        $file = dirname(__FILE__).'\sources\test1.pdf';
        $pdf_suite = new PdfSuite($file);
        Config::setOutputDirectory(__DIR__.'/results/test-'.date('m-d-Y_H-i-s'), true);

        $pdf_suite->getPdfToPsConverter()->convert();
    }

    public function testPdfToSvgConverter()
    {
        $file = dirname(__FILE__).'\sources\test1.pdf';
        $pdf_suite = new PdfSuite($file);
        Config::setOutputDirectory(__DIR__.'/results/test-'.date('m-d-Y_H-i-s'), true);

        $pdf_suite->getPdfToSvgConverter()->convert();
    }

}