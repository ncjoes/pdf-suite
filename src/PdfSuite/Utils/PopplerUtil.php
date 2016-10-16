<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:15 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use NcJoes\PdfSuite\PdfSuite;
use NcJoes\PopplerPhp\PopplerUtil as PopplerPhpUtil;

abstract class PopplerUtil
{
    protected $util;

    public function __construct(PopplerPhpUtil $util)
    {
        $this->util = $util;

        return $this;
    }

    protected function pdfInfo()
    {
        return PdfSuite::instance($this->util->sourcePdf())->getPdfInfo();
    }
}