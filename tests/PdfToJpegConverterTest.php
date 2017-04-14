<?php
/**
 * pdf-suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/16/2016
 * Time:    7:47 PM
 **/

namespace NcJoes\PdfSuite\Tests;

class PdfToJpegConverterTest extends PdfSuiteTestBase
{
    public function testPdfToJpegConverter()
    {
        $converter = $this->suite->getPdfToJpegConverter();
        $converter->setPageRange(1, 16);
        $this->assertEquals(16, $converter->convert()->getItems()->count());
    }
}