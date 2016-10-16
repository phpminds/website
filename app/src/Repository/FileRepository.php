<?php

namespace PHPMinds\Repository;


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

    public function has($filename)
    {
        if (file_exists($this->path . $filename) ) {
            return true;
        }

        return false;
    }
}