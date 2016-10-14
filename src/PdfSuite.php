<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    1:47 PM
 **/

namespace NcJoes\PdfSuite;

use NcJoes\PdfSuite\Exceptions\FileNotFoundException;
use NcJoes\PhpPoppler\Constants as C;
use NcJoes\PhpPoppler\Helpers as H;
use NcJoes\PhpPoppler\PdfDetach;
use NcJoes\PhpPoppler\PdfFonts;
use NcJoes\PhpPoppler\PdfImages;
use NcJoes\PhpPoppler\PdfInfo;
use NcJoes\PhpPoppler\PdfSeparate;
use NcJoes\PhpPoppler\PdfToCairo;
use NcJoes\PhpPoppler\PdfToHtml;
use NcJoes\PhpPoppler\PdfToPpm;
use NcJoes\PhpPoppler\PdfToPs;
use NcJoes\PhpPoppler\PdfToText;
use NcJoes\PhpPoppler\PdfUnite;
use NcJoes\PdfSuite\Wrappers\PdfInfo as PdfInfoWrapper;

class PdfSuite
{
    private $source_pdf;
    private $utils = [];
    private $output_filename_prefix;

    public function __construct($pdfFile = '')
    {
        if ($pdfFile != '') {
            return $this->open($pdfFile);
        }

        return $this;
    }

    public function open($pdfFile)
    {
        $real_path = realpath(H::parseDir($pdfFile));
        if (is_file($real_path)) {
            $this->source_pdf = $real_path;

            if (Config::isSet(C::OUTPUT_DIR))
                return $this;
            else
                return $this->outputDir(dirname($pdfFile));
        }
        throw new FileNotFoundException($pdfFile);
    }

    public function outputDir($dir = '')
    {
        if (!empty($dir) or $dir == C::DEFAULT) {
            Config::setOutputDirectory($dir);

            return $this;
        }

        return Config::getOutputDirectory();
    }

    public function outputFilenamePrefix($name = '')
    {
        if (!empty($name) and is_string($name)) {
            $this->output_filename_prefix = basename($name);

            Config::setOutputFilename($this->output_filename_prefix);

            return $this;
        }
        else {
            $base = basename($this->source_pdf);
            $default_name = str_replace('.pdf', '', $base) ?: '';

            return Config::getOutputFilename($default_name);
        }
    }

    public function getInfo()
    {
        $pdfInfo = $this->getUtil(C::PDF_INFO);

        return new PdfInfoWrapper($pdfInfo);
    }

    private function getUtil($name)
    {
        if (!array_key_exists($name, $this->utils)){
            $util_map = $this->utilMap();
            $util = (new $util_map[$name])->open($this->source_pdf);
            $this->utils[$name] = $util;
        }
        return $this->utils[$name];
    }

    private function utilMap()
    {
        return [
            C::PDF_DETACH   => PdfDetach::class,
            C::PDF_FONTS    => PdfFonts::class,
            C::PDF_IMAGES   => PdfImages::class,
            C::PDF_INFO     => PdfInfo::class,
            C::PDF_SEPARATE => PdfSeparate::class,
            C::PDF_TO_CAIRO => PdfToCairo::class,
            C::PDF_TO_HTML  => PdfToHtml::class,
            C::PDF_TO_PPM   => PdfToPpm::class,
            C::PDF_TO_PS    => PdfToPs::class,
            C::PDF_TO_TEXT  => PdfToText::class,
            C::PDF_UNITE    => PdfUnite::class,
        ];
    }
}