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
use NcJoes\PdfSuite\Contracts\FileContract;
use NcJoes\PdfSuite\Files\EpsFile;
use NcJoes\PdfSuite\Files\GenericFile;
use NcJoes\PdfSuite\Files\HtmlFile;
use NcJoes\PdfSuite\Files\JpegFile;
use NcJoes\PdfSuite\Files\PdfFile;
use NcJoes\PdfSuite\Files\PngFile;
use NcJoes\PdfSuite\Files\PostScriptFile;
use NcJoes\PdfSuite\Files\SvgFile;
use NcJoes\PopplerPhp\Constants as C;

abstract class File implements FileContract
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

    public static function make($path)
    {
        if (is_file($path)) {
            $class = self::getFileClass($path);

            return new $class($path);
        }
        throw new Exception('Supplied path does not point to an existing file');
    }

    public static function getFileClass($path)
    {
        $extension = strtolower(self::filesystem()->extension($path));
        switch ($extension) {
            case 'eps' :
                $class = EpsFile::class;
            break;
            case 'html' :
                $class = HtmlFile::class;
            break;
            case 'jpg' :
                $class = JpegFile::class;
            break;
            case 'pdf':
                $class = PdfFile::class;
            break;
            case 'png':
                $class = PngFile::class;
            break;
            case 'ps':
                $class = PostScriptFile::class;
            break;
            case 'svg':
                $class = SvgFile::class;
            break;
            default :
                $class = GenericFile::class;
        }

        return $class;
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

    public function parentDirectory()
    {
        return self::filesystem()->dirname($this->path());
    }

    public function rename($new_name, $extension = null)
    {
        $new_path = $this->parentDirectory().C::DS.$new_name.'.'.(is_null($extension) ? $this->extension() : $extension);

        if ($this->filesystem()->move($this->path(), $new_path)) {
            $this->setPath($new_path);

            return true;
        }

        return false;
    }

    public function delete()
    {
        return $this->filesystem()->delete($this->path());
    }

    public function moveTo($new_dir)
    {
        $fs = $this->filesystem();

        return $fs->move($this->path(), $new_dir.C::DS.$this->basename());
    }

    public function save()
    {
        $status = self::filesystem()->put($this->path(), $this->get(), true);
        if ($status) {
            $this->contents = null;
        }

        return $status;
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

    public function put($contents, $save = null)
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

    public function __destruct()
    {
        if (Config::shouldCleanupOnExit())
            $this->delete();
        else
            $this->save();
    }
}