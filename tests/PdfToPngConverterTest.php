<?php
/**
 * pdf-suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/16/2016
 * Time:    7:48 PM
 **/

namespace NcJoes\PdfSuite\Tests;

class PdfToPngConverterTest extends PdfSuiteTestBase
{
    public function testPdfToPngConverter()
    {
        $converter = $this->suite->getPdfToPngConverter();
        $converter->setPageRange(1, 16);
        $this->assertEquals(16, $converter->convert()->getItems()->count());
    }
}