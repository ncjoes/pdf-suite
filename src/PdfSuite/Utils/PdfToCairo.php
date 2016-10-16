<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:08 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use NcJoes\PdfSuite\Directory;
use NcJoes\PopplerPhp\PdfToCairo as PdfToCairoUtil;

abstract class PdfToCairo extends PopplerUtil
{
    public function __construct(PdfToCairoUtil $util)
    {
        return parent::__construct($util);
    }

    /**
     * @return Directory
     */
    abstract public function convert();
}