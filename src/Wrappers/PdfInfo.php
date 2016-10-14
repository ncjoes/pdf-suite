<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    4:03 PM
 **/

namespace NcJoes\PdfSuite\Wrappers;

use NcJoes\PhpPoppler\PdfInfo as DataObject;

class PdfInfo
{
    private $pdfInfo;

    public function __construct(DataObject $pdfInfo)
    {
        $this->pdfInfo = $pdfInfo;
    }

    public function getInfo()
    {
        return $this->pdfInfo->getInfo();
    }

    public function getTitle()
    {
        return $this->pdfInfo->getTitle();
    }

    public function getAuthors()
    {
        return $this->pdfInfo->getAuthors();
    }

    public function getCreator()
    {
        return $this->pdfInfo->getCreator();
    }

    public function getProducer()
    {
        return $this->pdfInfo->getProducer();
    }

    public function getCreationDate()
    {
        return $this->pdfInfo->getCreationDate();
    }

    public function getModificationDate()
    {
        return $this->pdfInfo->getTitle();
    }

    public function isTagged()
    {
        return $this->pdfInfo->isTagged();
    }

    public function hasJavaScript()
    {
        return $this->pdfInfo->hasJavaScript();
    }

    public function getNumOfPages()
    {
        return $this->pdfInfo->getNumOfPages();
    }

    public function isEncrypted()
    {
        return $this->pdfInfo->isEncrypted();
    }

    public function getPageSize()
    {
        return $this->pdfInfo->getPageSize();
    }

    public function getPageSizeUnit()
    {
        return $this->pdfInfo->getSizeUnit();
    }

    public function getPageWidth()
    {
        return $this->pdfInfo->getPageWidth();
    }

    public function getPageHeight()
    {
        return $this->pdfInfo->getPageHeight();
    }

    public function getPageRot()
    {
        return $this->pdfInfo->getPageRot();
    }

    public function getFileSize()
    {
        return $this->pdfInfo->getFileSize();
    }

    public function isOptimized()
    {
        return $this->pdfInfo->isOptimized();
    }

    public function getPdfVersion()
    {
        return $this->pdfInfo->getPdfVersion();
    }
}