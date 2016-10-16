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
        $converter->setPageRange(20, 33);
        $this->assertEquals(33-19, $converter->convert()->getItems()->count());
    }
}