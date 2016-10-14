<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    8:44 PM
 **/

namespace NcJoes\PdfSuite\Wrappers;

use NcJoes\PhpPoppler\PdfToCairo as PdfToCairoUtil;

class PdfToJpeg extends PdfToCairo
{
    public function convert()
    {
        /**
         * @var $util PdfToCairoUtil
         */
        $util = $this->util;
        $util->generateJPG();
    }
}