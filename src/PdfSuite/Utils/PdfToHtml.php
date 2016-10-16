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
use NcJoes\PdfSuite\Helpers as H;
use NcJoes\PopplerPhp\Constants as C;
use NcJoes\PopplerPhp\PdfToHtml as PdfToHtmlUtil;

class PdfToHtml extends PopplerUtil
{
    private $use_default = true;

    public function __construct(PdfToHtmlUtil $util)
    {
        return parent::__construct($util);
    }

    public function useDefaultSettings($yes = true)
    {
        $this->use_default = boolval($yes);

        return $this;
    }

    public function convert()
    {
        /**
         * @var $util PdfToHtmlUtil
         */
        $util = $this->util();
        $util->suppressConsoleOutput();
        $util->generateSingleDocument();

        if($this->use_default) {
            $util->splashImageFormat('jpg');
            $util->exchangePdfLinks();
            $util->noFrames();
        }

        $directory = $this->getOutputSubDir();

        $num_pages = $this->pdfInfo()->getNumOfPages();
        $pad_width = strlen((string)$num_pages);

        for ($page = $this->startPage(); $page <= $this->stopPage(); $page++) {

            $number = H::padNumber($page, $pad_width);
            $page_name = 'page-'.$number;

            $util->setOutputSubDir($directory->basename().C::DS.$page_name);
            $util->setOutputFilenamePrefix($page_name);
            $util->startFromPage($page);
            $util->stopAtPage($page);
            $util->generate();

            $page_directory = new Directory($util->getOutputPath());
            $page_directory = $this->fixPageBgImage($page_directory, $page);

            $directory->addItem($page_directory);
        }

        return $directory;
    }

    protected function fixPageBgImage(Directory $directory, $p)
    {
        $extension = '.'.$this->util()->getOption(C::_FMT);
        $orig_name = $directory->basename().H::popplerNumber($p).$extension;
        $new_name = $directory->basename().H::popplerNumber(1).$extension;
        $old = $directory->path().C::DS.$orig_name;

        $new = $directory->path().C::DS.$new_name;
        if (is_file($old)) {
            rename($old, $new);
        }

        $this->cleanupImageDumps($directory, [basename($new)]);

        return $directory;
    }

    protected function cleanupImageDumps(Directory $directory, array $except)
    {
        foreach ($directory->getItems() as $file) {
            if (!in_array($file->basename(), $except) and $file->extension() != 'html')
                $file->delete();
        }
        $directory->refresh();
    }
}