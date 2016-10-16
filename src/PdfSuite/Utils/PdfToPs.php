<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:52 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use NcJoes\PdfSuite\Directory;
use NcJoes\PdfSuite\Files\PostScriptFile;
use NcJoes\PopplerPhp\Constants as C;
use NcJoes\PopplerPhp\PdfToCairo as PdfToCairoUtil;

class PdfToPs extends PdfToCairo
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

        $num_pages = $this->pdfInfo()->getNumOfPages();
        for ($page = 1; $page <= $num_pages; $page++) {
            $number = str_pad($page, strlen((string)$num_pages), '0', STR_PAD_LEFT);
            $util->setOutputFilenamePrefix('page-'.$number);
            $util->startFromPage($page);
            $util->stopAtPage($page);
            $util->generatePS();

            $path = $util->getOutputPath().C::DS.$util->getOutputFilenamePrefix().$util->outputExtension();
            $directory->addItem(new PostScriptFile($path));
        }

        return $directory;
    }
}