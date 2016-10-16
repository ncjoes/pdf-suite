<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:50 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use Illuminate\Filesystem\Filesystem;
use NcJoes\PdfSuite\Directory;
use NcJoes\PdfSuite\Files\PngFile;
use NcJoes\PopplerPhp\Helpers;
use NcJoes\PopplerPhp\PdfToCairo as PdfToCairoUtil;

class PdfToPng extends PdfToCairo
{
    /**
     * @return Directory
     */
    public function convert()
    {
        /**
         * @var $util PdfToCairoUtil
         */
        $util = $this->util;

        $util->setOutputSubDir(uniqid());
        $directory = new Directory($util->getOutputPath(), true);

        $util->setOutputFilenamePrefix('page');
        $util->generateJPG();

        $fileSystem = new Filesystem;
        $files = $fileSystem->files($directory->path());

        foreach ($files as $file) {
            $file = Helpers::parseFileRealPath($file);
            $directory->addItem(new PngFile($file));
        }

        return $directory;
    }
}