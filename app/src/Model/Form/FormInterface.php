<?php

namespace PHPMinds\Model\Form;


interface FormInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function populate(array $data);

    /**
     * @return bool
     */
    public function isValid();
}