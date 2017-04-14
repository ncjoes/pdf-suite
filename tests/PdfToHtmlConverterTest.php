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

    public function testSinglePageConverterOption()
    {
        /*
        $converter = $this->suite->getPdfToHtmlConverter();

        $converter->setPageRange(1, 16)->useDefaultSettings();
        $converter->setOutputSubDir('testSinglePageConverterOption');
        $directory = $converter->convert($converter::MODE_SINGLE_PAGE_PER_DOC);
        $this->assertEquals(2 * (16), $directory->count());
        */
    }

    public function testCombinedPageConverterOption()
    {
        $converter = $this->suite->getPdfToHtmlConverter();

        $converter->setPageRange(1, 16)->useDefaultSettings();
        $converter->setOutputSubDir('testCombinedPageConverterOption');
        $converter->convert($converter::MODE_MULTI_PAGED_SINGLE_DOC);
    }

    public function testComplexPageConverterOption()
    {
        $converter = $this->suite->getPdfToHtmlConverter();

        $converter->setPageRange(1, 16)->useDefaultSettings();
        $converter->setOutputSubDir('testComplexPageConverterOption');
        $converter->convert($converter::MODE_COMPLEX_DOCUMENT);
    }
}