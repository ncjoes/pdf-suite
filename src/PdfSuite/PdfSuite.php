<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    1:47 PM
 **/

namespace NcJoes\PdfSuite;

use NcJoes\PdfSuite\Utils\PdfInfo as PdfInfoWrapper;
use NcJoes\PdfSuite\Utils\PdfJoin as PdfJoiner;
use NcJoes\PdfSuite\Utils\PdfSplit as PdfSplitter;
use NcJoes\PdfSuite\Utils\PdfToEps as PdfToEPSConverter;
use NcJoes\PdfSuite\Utils\PdfToHtml as PdfToHtmlConverter;
use NcJoes\PdfSuite\Utils\PdfToJpeg as PdfToJPEGConverter;
use NcJoes\PdfSuite\Utils\PdfToPng as PdfToPNGConverter;
use NcJoes\PdfSuite\Utils\PdfToPs as PdfToPSConverter;
use NcJoes\PdfSuite\Utils\PdfToSvg as PdfToSvgConverter;
use NcJoes\PdfSuite\Utils\PdfToTiff as PdfToTIFFConverter;
use NcJoes\PopplerPhp\Constants as C;
use NcJoes\PopplerPhp\Helpers as H;
use NcJoes\PopplerPhp\PdfDetach;
use NcJoes\PopplerPhp\PdfFonts;
use NcJoes\PopplerPhp\PdfImages;
use NcJoes\PopplerPhp\PdfInfo;
use NcJoes\PopplerPhp\PdfSeparate;
use NcJoes\PopplerPhp\PdfToCairo;
use NcJoes\PopplerPhp\PdfToHtml;
use NcJoes\PopplerPhp\PdfToPpm;
use NcJoes\PopplerPhp\PdfToPs;
use NcJoes\PopplerPhp\PdfToText;
use NcJoes\PopplerPhp\PdfUnite;

class PdfSuite
{
    private        $source_pdf;
    private        $utils = [];
    private static $instance;

    public function __construct($pdf_file_path = '')
    {
        if ($pdf_file_path !== '') {
            return $this->open($pdf_file_path);
        }
        self::$instance = $this;

        return $this;
    }

    public static function instance($pdf_file_path = '')
    {
        if (!is_object(self::$instance)) {
            self::$instance = new static($pdf_file_path);
        }

        return self::$instance;
    }

    public function open($pdfFile)
    {
        $real_path = H::parseFileRealPath($pdfFile);

        if (is_file($real_path)) {
            $this->source_pdf = $real_path;

            if (!Config::isKeySet(C::OUTPUT_DIR)) {
                Config::setOutputDirectory(dirname($pdfFile));
            }

            return $this;
        }
        throw new PdfSuiteException("File not found :".$pdfFile);
    }

    public function outputDir($dir = '')
    {
        if (!empty($dir)) {
            Config::setOutputDirectory($dir);

            return $this;
        }

        return Config::getOutputDirectory();
    }

    public function getPdfInfo()
    {
        $pdfInfoUtil = $this->getUtil(C::PDF_INFO);

        return new PdfInfoWrapper($pdfInfoUtil);
    }

    public function getPdfJoiner()
    {
        $pdfUniteUtil = $this->getUtil(C::PDF_UNITE);

        return new PdfJoiner($pdfUniteUtil);
    }

    public function getPdfSplitter()
    {
        $pdfSeparateUtil = $this->getUtil(C::PDF_SEPARATE);

        return new PdfSplitter($pdfSeparateUtil);
    }

    public function getPdfToEpsConverter()
    {
        $pdfToCairoUtil = $this->getUtil(C::PDF_TO_CAIRO);

        return new PdfToEPSConverter($pdfToCairoUtil);
    }

    public function getPdfToHtmlConverter()
    {
        $pdfToHtmlUtil = $this->getUtil(C::PDF_TO_HTML);

        return new PdfToHtmlConverter($pdfToHtmlUtil);
    }

    public function getPdfToJpegConverter()
    {
        $pdfToCairoUtil = $this->getUtil(C::PDF_TO_CAIRO);

        return new PdfToJPEGConverter($pdfToCairoUtil);
    }

    public function getPdfToPngConverter()
    {
        $pdfToCairoUtil = $this->getUtil(C::PDF_TO_CAIRO);

        return new PdfToPNGConverter($pdfToCairoUtil);
    }

    public function getPdfToPsConverter()
    {
        $pdfToCairoUtil = $this->getUtil(C::PDF_TO_CAIRO);

        return new PdfToPSConverter($pdfToCairoUtil);
    }

    public function getPdfToSvgConverter()
    {
        $pdfToSvgUtil = $this->getUtil(C::PDF_TO_CAIRO);

        return new PdfToSvgConverter($pdfToSvgUtil);
    }

    public function getPdfToTiffConverter()
    {
        $pdfToCairoUtil = $this->getUtil(C::PDF_TO_CAIRO);

        return new PdfToTIFFConverter($pdfToCairoUtil);
    }

    private function getUtil($name)
    {
        if (!array_key_exists($name, $this->utils)) {
            $util_map = $this->utilMap();
            $util = (new $util_map[ $name ])->open($this->source_pdf);
            $this->utils[ $name ] = $util;
        }

        return $this->utils[ $name ];
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
