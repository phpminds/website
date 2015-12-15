<?php

namespace App\Repository;


class FileRepository
{

    private $path;

    public function __construct($path)
    {
        $this->path = realpath($path) . '/';
    }

    public function save($filename, $contents, $flags = 0)
    {
        file_put_contents($this->path . $filename, $contents);
    }

    public function get($filename)
    {
        return file_get_contents($this->path . $filename);
    }
}