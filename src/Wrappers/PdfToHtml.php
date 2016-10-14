<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:48 PM
 **/

namespace NcJoes\PdfSuite\Wrappers;

use NcJoes\PhpPoppler\PdfToHtml as PdfToHtmlUtil;

class PdfToHtml extends PhpPopplerUtil
{
    public function __construct(PdfToHtmlUtil $util)
    {
        return parent::__construct($util);
    }

    public function convert()
    {
        // TODO: Implement convert() method.
    }
}