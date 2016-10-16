<?php
/**
 * pdf-suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/16/2016
 * Time:    7:46 PM
 **/

namespace NcJoes\PdfSuite\Tests;

class PdfToHtmlConverterTest extends PdfSuiteTestBase
{
    public function testInvalidPageRangeException()
    {
        try {
            $this->suite->getPdfToHtmlConverter()->setPageRange(50, 20);
        }
        catch (\Exception $exception) {
            $this->addToAssertionCount(1);
            //var_dump($exception->getMessage());
        }
    }
    public function testPdfToHtmlConverter()
    {
        $converter = $this->suite->getPdfToHtmlConverter();

        $converter->setPageRange(1, 20)->useDefaultSettings();
        $directory = $converter->convert();
        $this->assertEquals(20, $directory->count());
    }
}