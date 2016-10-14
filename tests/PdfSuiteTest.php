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
    }

    public function testGetInfo()
    {
        $file = realpath(dirname(__FILE__).'\sources\test1.pdf');
        $pdf_suite = new PdfSuite($file);
        $pdf_info = $pdf_suite->getPdfInfo();

        $this->assertArrayHasKey('pages', $pdf_info->getInfo());
    }

    public function testGetters()
    {
        $file = dirname(__FILE__).'\sources\test1.pdf';
        $pdf_suite = new PdfSuite($file);

        $pdf_info = $pdf_suite->getPdfInfo();

        $info = [
            'Authors'           => $pdf_info->getAuthors(),
            'Creation Date'     => $pdf_info->getCreationDate(),
            'Creator'           => $pdf_info->getCreator(),
            'File Size'         => $pdf_info->getFileSize(),
            'Modification Date' => $pdf_info->getModificationDate(),
            'Num. of Pages'     => $pdf_info->getNumOfPages(),
            'Page Rot'          => $pdf_info->getPageRot(),
            'Page Size'         => $pdf_info->getPageSize(),
            'PDF Version'       => $pdf_info->getPdfVersion(),
            'Producer'          => $pdf_info->getProducer(),
            'Is Tagged?'        => (int)$pdf_info->isTagged(),
            'Is Optimized'      => (int)$pdf_info->isOptimized(),
            'Page Width'        => $pdf_info->getPageWidth(),
            'Page Height'       => $pdf_info->getPageHeight(),
            'Page Size Unit'    => $pdf_info->getPageSizeUnit(),
        ];

        print_r($info);
    }

}