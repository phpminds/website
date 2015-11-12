<?php
namespace App\Service;

class ContentService
{

	/**
	* Content folder for the markdown files; 
	*/
	private $contentFolder; 


	private $parsedown; 


	public function __construct(\Parsedown $parsedown, $contentFolder)
	{
		$this->parsedown = $parsedown;
		$this->contentFolder = $contentFolder; 
	}

	public function getContentByName($name) 
	{
		$content = "";

		if(!empty($filename = $this->createFileNameFromName($name))){

			$content = file_get_contents($filename);
		}

		return $this->parsedown->text($content);


	}

	private function createFileNameFromName($name)
	{
		$filename = $this->contentFolder . $name;

		if(!preg_match('/^.*\.md/i', $name))
		{

			 $filename .= ".md"; 
		}
		
		

		if(file_exists($filename))
		{
			return $filename;
		}

	}



}