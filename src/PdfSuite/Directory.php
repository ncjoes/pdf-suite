<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/15/2016
 * Time:    1:10 AM
 **/

namespace NcJoes\PdfSuite;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class Directory
{
    /**
     * @var $path string
     */
    private $path;

    /**
     * @var $files Collection
     */
    private $files;

    /**
     * @var $filesystem Filesystem
     */
    private static $filesystem;

    public function __construct($path, $new = false)
    {
        if ($new) {
            $this->filesystem()->makeDirectory($path);
        }
        $this->setPath($path);

        return $this;
    }

    public function path()
    {
        return $this->path;
    }

    protected function setPath($path)
    {
        if (is_dir($path)) {
            $this->path = $path;

            return $this;
        }
        throw new Exception('Supplied path does not point to a directory');
    }

    public function saveAllFiles()
    {
        foreach ($this->files() as $file) {
            $file->save();
        }
    }

    public function moveFilesTo($new_dir)
    {
        foreach ($this->files() as $file) {
            if (!$file->moveTo($new_dir))
                return false;
        }

        return true;
    }

    public function files()
    {
        if (!is_object($this->files)) {
            $this->files = new Collection;
        }

        return $this->files;
    }

    public function putFile($index, File $file)
    {
        if ($this->files->offsetExists($index)) {
            $this->files->put($index, $file);

            return $this;
        }

        throw new Exception('Index out of bound: '.$index);
    }

    public function addFile(File $file)
    {
        $this->files()->push($file);
    }

    protected function filesystem()
    {
        if (!is_object(self::$filesystem)) {
            self::$filesystem = new Filesystem;
        }

        return self::$filesystem;
    }

    function __destruct()
    {
        if (Config::shouldCleanupOnExit())
            $this->filesystem()->deleteDirectory($this->path());
    }
}