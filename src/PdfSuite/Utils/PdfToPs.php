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
use NcJoes\PdfSuite\Helpers as H;
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
        $util = $this->util();

        $directory = $this->getOutputSubDir();

        $num_pages = $this->pdfInfo()->getNumOfPages();
        $pad_width = strlen((string)$num_pages);

        for ($page = $this->startPage(); $page <= $this->stopPage(); $page++) {

            $number = H::padNumber($page, $pad_width);
            $page_name = 'page-'.$number;

            $util->setOutputFilenamePrefix($page_name);
            $util->startFromPage($page);
            $util->stopAtPage($page);
            $util->generatePS();

            $path = $util->getOutputPath().C::DS.$util->getOutputFilenamePrefix().$util->outputExtension();
            $directory->addItem(new PostScriptFile($path));
        }

        return $directory;
    }
}