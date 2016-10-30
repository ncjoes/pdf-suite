<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    4:03 PM
 **/

namespace NcJoes\PdfSuite\Utils;

use NcJoes\PopplerPhp\PdfInfo as PdfInfoUtil;

class PdfInfo extends PopplerUtil
{
    public function __construct(PdfInfoUtil $util)
    {
        return parent::__construct($util);
    }

    public function getInfo()
    {
        return $this->util()->getInfo();
    }

    public function getTitle()
    {
        return $this->util()->getTitle();
    }

    public function getAuthors()
    {
        return $this->util()->getAuthors();
    }

    public function getCreator()
    {
        return $this->util()->getCreator();
    }

    public function getProducer()
    {
        return $this->util()->getProducer();
    }

    public function getCreationDate()
    {
        return $this->util()->getCreationDate();
    }

    public function getModificationDate()
    {
        return $this->util()->getTitle();
    }

    public function isTagged()
    {
        return $this->util()->isTagged();
    }

    public function hasJavaScript()
    {
        return $this->util()->hasJavaScript();
    }

    public function getNumOfPages()
    {
        return $this->util()->getNumOfPages();
    }

    public function isEncrypted()
    {
        return $this->util()->isEncrypted();
    }

    public function getPageSize()
    {
        return $this->util()->getPageSize();
    }

    public function getPageSizeUnit()
    {
        return $this->util()->getSizeUnit();
    }

    public function getPageWidth()
    {
        return $this->util()->getPageWidth();
    }

    public function getPageHeight()
    {
        return $this->util()->getPageHeight();
    }

    public function getPageRot()
    {
        return $this->util()->getPageRot();
    }

    public function getFileSize()
    {
        return $this->util()->getFileSize();
    }

    public function isOptimized()
    {
        return $this->util()->isOptimized();
    }

    public function getPdfVersion()
    {
        return $this->util()->getPdfVersion();
    }

    public function getPageOrientation()
    {
        $width = $this->getPageWidth();
        $height = $this->getPageHeight();
        if ($width and $height) {
            $ratio = $width / $height;

            if ($ratio > 1.1)
                return 'L';
            elseif ($ratio < 0.9)
                return 'P';
            else
                return 'S';
        }

        return null;
    }
}