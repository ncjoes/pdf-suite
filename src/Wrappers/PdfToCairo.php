<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:08 PM
 **/

namespace NcJoes\PdfSuite\Wrappers;

use NcJoes\PhpPoppler\PdfToCairo as PdfToCairoUtil;

abstract class PdfToCairo extends PhpPopplerUtil
{
    public function __construct(PdfToCairoUtil $util)
    {
        return parent::__construct($util);
    }

    abstract public function convert();
}