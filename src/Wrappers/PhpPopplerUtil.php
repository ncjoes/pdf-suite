<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:15 PM
 **/

namespace NcJoes\PdfSuite\Wrappers;

use NcJoes\PhpPoppler\PopplerUtil;

abstract class PhpPopplerUtil
{
    protected $util;

    public function __construct(PopplerUtil $util)
    {
        $this->util = $util;

        return $this;
    }
}