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
        if ($new and !file_exists($path)) {
            $this->filesystem()->makeDirectory($path);
        }
        $this->setPath($path);

        return $this;
    }

    public function hash()
    {
        return $this->mkKey(Helpers::parseDirName($this->path()));
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

    public function moveTo($new_path)
    {
        $fs = $this->filesystem();

        return $fs->moveDirectory($this->path(), $new_path);
    }

    public function copyTo($new_path)
    {
        $fs = $this->filesystem();

        return $fs->copyDirectory($this->path(), $new_path);
    }

    public function save()
    {
        $net_saved = 0;
        foreach ($this->items() as $file) {
            $net_saved += $file->save();
        }

        return $net_saved;
    }

    public function saveAs($new_path)
    {
        if ($this->filesystem()->makeDirectory($new_path, 0755, true, true)) {
            foreach ($this->items() as $file) {
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
        foreach ($this->items() as $file) {
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

    public static function mkKey($path)
    {
        return md5($path);
    }

    protected function setPath($path)
    {
        if (is_dir($path)) {
            $this->path = $path;

            return $this;
        }
        throw new PdfSuiteException('Supplied path does not point to a directory');
    }

    protected function items()
    {
        if (!is_object($this->files)) {
            $this->files = collect();
            $this->refresh();
        }

        return $this->files;
    }

    public function refresh()
    {
        if ($this->files) {
            foreach ($this->files as $file) {
                if (!$this->filesystem()->exists($file->path()))
                    $this->files->offsetUnset($file->hash());
            }
        }

        $sub_directories = $this->filesystem()->directories($this->path());
        foreach ($sub_directories as $sub_directory) {
            if (is_dir($sub_directory) and !is_file($sub_directory))
                $this->addItem(new Directory($sub_directory));
        }

        $files = $this->filesystem()->files($this->path());
        foreach ($files as $file) {
            if (is_file($file) and !is_dir($file))
                $this->addItem(File::make($file));
        }
    }

    public function getItems()
    {
        return $this->items();
    }

    protected function item($hash)
    {
        if ($this->items()->offsetExists($hash)) {
            /**
             * @var $file File
             */
            $file = $this->items()->get($hash);

            return $file;
        }

        throw new PdfSuiteException('Index not found: '.$hash);
    }

    public function getItem($hash)
    {
        return $this->item($hash);
    }

    public function addItem(FileContract $file)
    {
        $this->items()->put($file->hash(), $file);

        return $this;
    }

    public function count()
    {
        return $this->items()->count();
    }

    protected static function filesystem()
    {
        if (!is_object(self::$filesystem)) {
            self::$filesystem = new Filesystem;
        }

        return self::$filesystem;
    }
}
