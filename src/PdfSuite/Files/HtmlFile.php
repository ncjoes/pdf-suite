<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    2:44 PM
 **/

namespace NcJoes\PdfSuite\Files;

use NcJoes\PdfSuite\File;
use Symfony\Component\DomCrawler\Crawler as DOM;

class HtmlFile extends File
{
    private $dom;

    public function __construct($path)
    {
        parent::__construct($path);
    }

    public function getDOM()
    {
        if(!$this->dom) {
            $this->save();
            $this->dom = new DOM();
            $this->dom->addHtmlContent($this->get());
        }

        return $this->dom;
    }

    public function putDOM(DOM $dom, $save = false)
    {
        return $this->put($dom->html(), $save);
    }

    public static function cleanEmptyLines($html)
    {
        $content = [];
        $lines = explode("\n", $html);
        foreach ($lines as $line) {
            $line = trim($line);
            if(strlen($line)) {
                $content[] = $line;
            }
        }
        return implode("\n", $content);
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->dom);
    }

}