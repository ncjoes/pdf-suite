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
        $this->put(str_replace(["Â"], "", $this->get()));

        $dom = new DOM($this->get());
        $this->dom = $dom;
    }

    public function getDOM()
    {
        $this->dom->clear();
        $this->dom->addHtmlContent($this->get());

        return $this->dom;
    }

    public function putDOM(DOM $dom, $save = false)
    {
        return $this->put($dom->html(), $save);
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->dom);
    }

}