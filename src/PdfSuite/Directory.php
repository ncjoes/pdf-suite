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
use NcJoes\PdfSuite\Contracts\FileContract;
use NcJoes\PopplerPhp\Constants as C;

class Directory implements FileContract
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

        $sub_directories = $this->filesystem()->directories($this->path());
        foreach ($sub_directories as $sub_directory) {
            $this->addItem(new Directory($sub_directory));
        }

        $files = $this->filesystem()->files($this->path());
        foreach ($files as $file) {
            $this->addItem(File::make($file));
        }

        return $this;
    }

    public function path()
    {
        return $this->path;
    }

    public function basename()
    {
        return $this->filesystem()->basename($this->path());
    }

    public function extension()
    {
        return null;
    }

    public function parentDirectory()
    {
        return self::filesystem()->dirname($this->path());
    }

    public function rename($new_name, $extension = null)
    {
        $new_path = $this->parentDirectory().C::DS.$new_name;

        if ($this->filesystem()->moveDirectory($this->path(), $new_path)) {
            $this->setPath($new_path);

            return true;
        }

        return false;
    }

    public function delete()
    {
        return $this->filesystem()->deleteDirectory($this->path());
    }

    public function moveTo($new_dir)
    {
        $fs = $this->filesystem();

        return $fs->moveDirectory($this->path(), $new_dir.C::DS.$this->basename());
    }

    public function save()
    {
        $net_saved = 0;
        foreach ($this->files() as $file) {
            $net_saved += $file->save();
        }

        return $net_saved;
    }

    public function saveAs($new_path)
    {
        if ($this->filesystem()->makeDirectory($new_path, 0755, true, true)) {
            foreach ($this->files() as $file) {
                if (!$file->save())
                    return false;
            }

            return true;
        }

        return false;
    }

    public function get()
    {
        $paths = [];
        foreach ($this->files() as $file) {
            array_push($paths, $file->path());
        }

        return $paths;
    }

    public function put($contents)
    {
        return false;
    }

    public function append($contents)
    {
        return false;
    }

    public function prepend($contents)
    {
        return false;
    }

    protected function setPath($path)
    {
        if (is_dir($path)) {
            $this->path = $path;

            return $this;
        }
        throw new Exception('Supplied path does not point to a directory');
    }

    public function files()
    {
        if (!is_object($this->files)) {
            $this->files = new Collection;
        }

        return $this->files;
    }

    public function getFile($index)
    {
        if ($this->files()->offsetExists($index)) {
            /**
             * @var $file File
             */
            $file = $this->files()->get($index);

            return $file;
        }

        throw new Exception('Index out of bound: '.$index);
    }

    public function putItem($index, FileContract $file)
    {
        if ($this->files()->offsetExists($index)) {
            $this->files()->put($index, $file);

            return $this;
        }

        throw new Exception('Index out of bound: '.$index);
    }

    public function addItem(FileContract $file)
    {
        $this->files()->push($file);
    }

    public function count()
    {
        return $this->files()->count();
    }

    protected static function filesystem()
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