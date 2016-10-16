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
        $converter->setPageRange(15, 33);
        $this->assertEquals(33-14, $converter->convert()->getItems()->count());
    }
}