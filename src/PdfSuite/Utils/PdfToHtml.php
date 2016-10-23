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
use NcJoes\PdfSuite\File;
use NcJoes\PdfSuite\Files\HtmlFile;
use NcJoes\PdfSuite\Helpers as H;
use NcJoes\PopplerPhp\Constants as C;
use NcJoes\PopplerPhp\PdfToHtml as PdfToHtmlUtil;
use Symfony\Component\DomCrawler\Crawler;

class PdfToHtml extends PopplerUtil
{
    private $use_default = true;
    private $inline_css  = true;

    const MODE_COMPLEX_DOCUMENT       = 1;
    const MODE_SINGLE_PAGE_PER_DOC    = 2;
    const MODE_MULTI_PAGED_SINGLE_DOC = 3;

    public function __construct(PdfToHtmlUtil $util)
    {
        return parent::__construct($util);
    }

    public function useDefaultSettings($yes = true)
    {
        $this->use_default = boolval($yes);

        return $this;
    }

    public function useInlineCss($yes = true)
    {
        $this->inline_css = boolval($yes);

        return $this;
    }

    public function convert($mode = PdfToHtml::MODE_SINGLE_PAGE_PER_DOC)
    {
        /**
         * @var $util PdfToHtmlUtil
         */
        $util = $this->util();
        $util->suppressConsoleOutput();

        if ($this->use_default) {
            $this->applyDefaultSettings($util);
        }

        switch ($mode) {
            case self::MODE_COMPLEX_DOCUMENT :
                $this->convertToComplexDocument();
            break;

            case self::MODE_MULTI_PAGED_SINGLE_DOC;
                $this->convertToMultiPagedSingleDoc();
            break;

            case self::MODE_SINGLE_PAGE_PER_DOC :
            default:
                $this->convertToSinglePagedDocs();
        }

        $this->getOutputSubDir()->refresh();

        return $this->getOutputSubDir();
    }

    protected function applyDefaultSettings(PdfToHtmlUtil $util)
    {
        $util->splashImageFormat('jpg');
        $util->exchangePdfLinks();
        $util->noFrames();
        $this->inline_css;
    }

    protected function convertToComplexDocument()
    {
        /**
         * @var $util PdfToHtmlUtil
         */
        $util = $this->util();
        $util->generateComplexDocument();
        $util->unsetFlag(C::_NOFRAMES);
        $util->setOutputFilenamePrefix($this->getFilenamePrefix());
        $util->startFromPage($this->startPage());
        $util->stopAtPage($this->stopPage());
        $util->generate();
        $this->fixCombinedPageBgImage($this->getOutputSubDir());
    }

    protected function convertToMultiPagedSingleDoc()
    {
        /**
         * @var $util PdfToHtmlUtil
         */
        $util = $this->util();
        $util->generateSingleDocument();
        $util->unsetFlag(C::_NOFRAMES);
        $util->setOutputFilenamePrefix($this->getFilenamePrefix());
        $util->startFromPage($this->startPage());
        $util->stopAtPage($this->stopPage());
        $util->generate();
        $this->fixCombinedPageBgImage($this->getOutputSubDir());
    }

    protected function fixCombinedPageBgImage(Directory $directory)
    {
        $extension = '.'.$this->util()->getOption(C::_FMT);
        for ($p = $this->startPage(), $q = 1; $p <= $this->stopPage(); $p++, $q++) {
            $orig_name = $this->getFilenamePrefix().H::popplerNumber($p).$extension;
            $new_name = $this->getFilenamePrefix().H::popplerNumber($q).$extension;

            $old = $directory->path().C::DS.$orig_name;
            $new = $directory->path().C::DS.$new_name;

            if (is_file($old)) {
                rename($old, $new);
            }
        }
    }

    protected function convertToSinglePagedDocs()
    {
        /**
         * @var $util PdfToHtmlUtil
         */
        $util = $this->util();
        $util->generateSingleDocument();
        $directory = $this->getOutputSubDir();

        $num_pages = $this->pdfInfo()->getNumOfPages();
        $pad_width = strlen((string)$num_pages);
        for ($page = $this->startPage(); $page <= $this->stopPage(); $page++) {

            $number = H::padNumber($page, $pad_width);
            $page_name = $this->getFilenamePrefix().$number;

            $util->setOutputSubDir($directory->basename().C::DS.$page_name);
            $util->setOutputFilenamePrefix($page_name);
            $util->startFromPage($page);
            $util->stopAtPage($page);
            $util->generate();

            $page_directory = new Directory($util->getOutputPath());
            $page_directory = $this->fixSinglePageBgImage($page_directory, $page);
            $this->relocateSinglePageToParentDir($page_directory);
        }
        if ($this->inline_css) {
            $this->inlineCss($directory);
        }
    }

    protected function fixSinglePageBgImage(Directory $directory, $p)
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

    protected function relocateSinglePageToParentDir(Directory $directory)
    {
        foreach ($directory->getItems() as $file) {
            $file->moveTo($directory->parentDirectory().C::DS.$file->basename());
        }
        $directory->delete();
    }

    protected function inlineCss(Directory $directory)
    {
        $item_paths = $directory->get();
        foreach ($item_paths as $path) {
            if (is_dir($path)) {
                $this->inlineCss(new Directory($path));
                continue;
            }
            /**
             * @var $htmlFile HtmlFile
             */
            $htmlFile = File::make($path);
            if (get_class($htmlFile) == HtmlFile::class) {
                $page_number = (int)str_replace([$this->getFilenamePrefix(), '.html'], '', $htmlFile->basename());
                /**
                 * @var $dom Crawler
                 */
                $dom = $htmlFile->getDOM();
                $style_tags = $dom->filter('style');
                $main_div_id = 'page'.($page_number).'-div';
                $main_div_dom = $dom->filter('div#'.$main_div_id)->eq(0);

                $styles = '';
                for ($c = 0; $c < $style_tags->count(); $c++) {
                    $styles .= $style_tags->eq($c)->html();
                }
                $styles = str_replace(['<!--', '-->', "\n\n"], ['', '', "\n"], $styles);
                $body = $main_div_dom->html();
                $body_attr = 'id="page-'.$page_number.'" style="'.$main_div_dom->attr('style').'"';

                $build = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>';
                $build .= '<style type="text/css">'.$styles.'</style>';
                $build .= "\n\r";
                $build .= '<div '.$body_attr.'>'.$body.'</div>';
                $build = $this->cleanupDirtyCharacters($build);

                $build = $htmlFile->cleanEmptyLines($build);
                $htmlFile->put($build);

                $htmlFile->save();
            }
        }
        $directory->refresh();

        return $directory;
    }

    protected function cleanupDirtyCharacters($html)
    {
        return str_replace(["Â"], "", $html);
    }

}