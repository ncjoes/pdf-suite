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
            $this->path = Helpers::parseFileRealPath($path);

            return $this;
        }
        throw new PdfSuiteException('Supplied path does not point to an existing file');
    }

    public static function make($path)
    {
        if (is_file($path) and !is_dir($path)) {
            $class = self::getFileClass($path);

            return new $class($path);
        }
        throw new PdfSuiteException('Supplied path does not point to a file: '.$path);
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

    public function hash()
    {
        return $this->mkKey($this->path());
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

    public function moveTo($new_path)
    {
        $fs = $this->filesystem();

        return $fs->move($this->path(), $new_path);
    }

    public function copyTo($new_path)
    {
        $fs = $this->filesystem();

        return $fs->copy($this->path(), $new_path);
    }

    public function save()
    {
        $status = !is_file($this->path()) ? false : self::filesystem()->put($this->path(), $this->get(), true);
        if ($status) {
            $this->contents = $this->get();
        }

        return $status;
    }

    public function saveAs($new_path)
    {
        return $this->setPath($new_path)->save();
    }

    public function get()
    {
        if (empty($this->contents) and $this->filesystem()->exists($this->path())) {
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

    protected function mkKey($path)
    {
        return md5(Helpers::parseFileRealPath($path));
    }

    protected function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    protected static function filesystem()
    {
        if (!is_object(self::$filesystem)) {
            self::$filesystem = new Filesystem();
        }

        return self::$filesystem;
    }

    public function __destruct()
    {
        if (Config::shouldAutoSaveFilesOnExit()) {
            $this->save();
        }
    }
}
