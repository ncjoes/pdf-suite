<?php
/**
 * pdf-suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/16/2016
 * Time:    5:43 PM
 **/

namespace NcJoes\PdfSuite;

use NcJoes\PopplerPhp\Helpers as H;

abstract class Helpers extends H
{
    public static function popplerNumber($number)
    {
        $l = strlen((string)$number);
        $pad_width = $l < 3 ? 3 : $l;

        return self::padNumber($number, $pad_width);
    }

    public static function padNumber($number, $width)
    {
        return str_pad($number, $width, '0', STR_PAD_LEFT);
    }
}