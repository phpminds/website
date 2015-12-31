<?php

namespace PHPMinds\Config;


abstract class ConfigAbstract
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string
     */
    protected $section;

    public function __construct($section, array $config)
	{
        $this->section              = $section;
		$this->settings[$section]   = $config;
	}

    public function __set($property, $value)
    {
        return $this->settings[$this->section][$property] = $value;
    }

    public function __get($property)
    {
        return array_key_exists($property, $this->settings[$this->section])
            ? $this->settings[$this->section][$property]
            : null
            ;
    }
}