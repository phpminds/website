<?php

namespace App\Model;

class Auth
{
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function isValid()
    {
//        $this->repository->get('')
    }
}