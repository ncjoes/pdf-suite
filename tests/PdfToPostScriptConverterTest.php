<?php
/**
 * pdf-suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/16/2016
 * Time:    7:48 PM
 **/

namespace NcJoes\PdfSuite\Tests;

class PdfToPostScriptConverterTest extends PdfSuiteTestBase
{
    public function testPdfToPsConverter()
    {
        $converter = $this->suite->getPdfToPsConverter();
        $converter->setPageRange(1, 16);
        $this->assertEquals(17, $converter->convert()->getItems()->count());
    }
}