<?php
/**
 * pdf-suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/16/2016
 * Time:    7:50 PM
 **/

namespace NcJoes\PdfSuite\Tests;

class PdfInfoWrapperTest extends PdfSuiteTestBase
{
    public function testGetInfo()
    {
        $this->assertTrue(is_array($this->info->getInfo()));
        //var_dump($this->info->getInfo());
    }

}