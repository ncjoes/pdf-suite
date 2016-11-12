<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:15 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use NcJoes\PdfSuite\Config;
use NcJoes\PdfSuite\Directory;
use NcJoes\PdfSuite\PdfSuiteException;
use NcJoes\PdfSuite\PdfSuite;
use NcJoes\PopplerPhp\PopplerUtil as PopplerPhpUtil;

abstract class PopplerUtil
{
    private $util;
    private $directory;
    private $prefix;
    private $start_page;
    private $stop_page;

    public function __construct(PopplerPhpUtil $util)
    {
        $this->util = $util;

        return $this;
    }

    public function util()
    {
        return $this->util;
    }

    /**
     * @return PdfInfo
     */
    protected function pdfInfo()
    {
        return PdfSuite::instance($this->util()->sourcePdf())->getPdfInfo();
    }

    public function setOutputSubDir($var)
    {
        if (is_object($var) and get_class($var) == Directory::class) {
            $this->directory = $var;

            return $this;
        }

        $path = $this->util()->setOutputSubDir($var)->getOutputPath();
        $this->directory = new Directory($path, true);

        return $this;
    }

    public function getOutputSubDir()
    {
        if (!is_object($this->directory)) {
            $path = $this->util()->getOutputPath();
            $this->directory = new Directory($path, true);
        }

        return $this->directory;
    }

    public function setPageRange($start, $stop)
    {
        $num_pages = (int)$this->pdfInfo()->getNumOfPages();
        if ($start > $stop or $start > $num_pages) {
            throw new PdfSuiteException(
                "Invalid page range: start page of -{$start}- is either greater than 
                stop page -{$stop}- or greater number of pages in document -{$num_pages}-");
        }
        $this->start_page = $start < 1 ? 1 : $start;
        $this->stop_page = $stop > $num_pages ? $num_pages : $stop;

        return $this;
    }

    public function startPage()
    {
        if (!$this->start_page) {
            $this->start_page = 1;
        }

        return $this->start_page;
    }

    public function stopPage()
    {
        if (!$this->stop_page) {
            $this->stop_page = $this->pdfInfo()->getNumOfPages();
        }

        return $this->stop_page;
    }

    public function setFilenamePrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getFilenamePrefix()
    {
        return $this->prefix ?: str_replace('.pdf', '', basename($this->util()->sourcePdf()));
    }

    function __destruct()
    {
        if (Config::shouldCleanupOnExit()) {
            $this->getOutputSubDir()->delete();
        }
    }
}
