<?php
/**
 * pdf-suite
 *
 * Author:  Chukwuemeka Nwobodo (jcnwobodo@gmail.com)
 * Date:    10/16/2016
 * Time:    11:45 AM
 **/

namespace NcJoes\PdfSuite\Contracts;

interface FileContract
{
    public function hash();

    public function path();

    public function basename();

    public function extension();

    public function parentDirectory();

    public function rename($new_name, $extension=null);

    public function delete();

    public function moveTo($new_path);

    public function copyTo($new_path);

    public function save();

    public function saveAs($new_path);

    public function get();

    public function put($contents);

    public function append($contents);

    public function prepend($contents);

}