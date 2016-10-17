<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    7:15 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use NcJoes\PdfSuite\Directory;
use NcJoes\PdfSuite\Exception;
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
        if ($start > $stop) {
            throw new Exception("Invalid page range: start page of -{$start}- is greater than stop page -{$stop}-");
        }
        $num_pages = $this->pdfInfo()->getNumOfPages();
        $this->start_page = $start < 1 ? 1 : $start;
        $this->stop_page = $stop > $num_pages ? $num_pages : $stop;

        return $this;
    }

    public function startPage()
    {
        if (!$this->stop_page) {
            $this->stop_page = 1;
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
}