<?php
/**
 * PDF-Suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/14/2016
 * Time:    11:23 PM
 **/

namespace NcJoes\PdfSuite;

use Illuminate\Filesystem\Filesystem;
use NcJoes\PopplerPhp\Constants as C;

abstract class File
{
    /**
     * @var $path string
     */
    private $path;

    /**
     * @var $contents string
     */
    private $contents;

    /**
     * @var $filesystem Filesystem
     */
    private static $filesystem;

    public function __construct($path)
    {
        if (is_file($path)) {
            $this->path = $path;

            return $this;
        }
        throw new Exception('Supplied path does not point to an existing file');
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
        return self::filesystem()->extension($this->path());
    }

    public function getDirectoryName()
    {
        return self::filesystem()->dirname($this->path());
    }

    public function rename($new_name, $extension = null)
    {
        $new_path = $this->getDirectoryName().C::DS.$new_name.'.'.(is_null($extension) ? $this->extension() : $extension);

        if ($this->filesystem()->move($this->path(), $new_path)) {
            $this->setPath($new_path);

            return true;
        }

        return false;
    }

    public function delete()
    {
        $this->filesystem()->delete($this->path());
    }

    public function moveTo($new_dir)
    {
        $fs = $this->filesystem();

        return $fs->move($this->path(), $new_dir.C::DS.$this->basename());
    }

    public function save()
    {
        return self::filesystem()->put($this->path(), $this->get(), true);
    }

    public function saveAs($new_path)
    {
        return $this->setPath($new_path)->save();
    }

    public function get()
    {
        if (empty($this->contents)) {
            $this->contents = self::filesystem()->get($this->path());
        }

        return $this->contents;
    }

    public function put($contents, $save = false)
    {
        $this->contents = $contents;

        return $save ? $this->save() : sizeof($contents);
    }

    public function append($contents)
    {
        $this->contents = '';

        return $this->filesystem()->append($this->path(), $contents);
    }

    public function prepend($contents)
    {
        $this->contents = '';

        return $this->filesystem()->prepend($this->path(), $contents);
    }

    protected function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    protected function filesystem()
    {
        if (!is_object(self::$filesystem)) {
            self::$filesystem = new Filesystem();
        }

        return self::$filesystem;
    }

    function __destruct()
    {
        if (Config::shouldCleanupOnExit())
            $this->delete();
    }
}