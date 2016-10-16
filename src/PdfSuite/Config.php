<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    1:41 PM
 **/

namespace NcJoes\PdfSuite;

use NcJoes\PopplerPhp\Config as BaseConfig;

abstract class Config extends BaseConfig
{
    public static function doCleanupOnExit($yes = true)
    {
        self::set('ncjoes.psf_suite.cleanup_on_exit', $yes);
    }

    public static function shouldCleanupOnExit($default = true)
    {
        return self::get('ncjoes.psf_suite.cleanup_on_exit', $default);
    }

    public static function autoSaveFilesOnExit($yes = false)
    {
        self::set('ncjoes.psf_suite.auto_save_on_exit', $yes);
    }

    public static function shouldAutoSaveFilesOnExit($default = false)
    {
        return self::get('ncjoes.psf_suite.auto_save_on_exit', $default);
    }
}