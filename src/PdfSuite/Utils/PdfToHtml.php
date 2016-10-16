<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:48 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use NcJoes\PdfSuite\Directory;
use NcJoes\PdfSuite\Files\HtmlFile;
use NcJoes\PopplerPhp\Constants as C;
use NcJoes\PopplerPhp\PdfToHtml as PdfToHtmlUtil;

class PdfToHtml extends PopplerUtil
{
    public function __construct(PdfToHtmlUtil $util)
    {
        return parent::__construct($util);
    }

    public function useDefaultSettings()
    {
        /**
         * @var $util PdfToHtmlUtil
         */
        $util = $this->util;
        $util->suppressConsoleOutput();
        $util->generateSingleDocument();
        $util->noFrames();
        $util->splashImageFormat('png');

        return $this;
    }

    public function convert()
    {
        /**
         * @var $util PdfToHtmlUtil
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
            $util->generate();

            $path = $util->getOutputPath().C::DS.$util->getOutputFilenamePrefix().$util->outputExtension();
            $directory->addFile(new HtmlFile($path));
        }

        return $directory;
    }
}