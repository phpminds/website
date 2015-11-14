<?php

namespace App\Tests\Service;

use App\Service\ContentService;

class ContentServiceTest extends \PHPUnit_Framework_TestCase
{

    private $contentService;


	public function setUp()
	{
        $mockedParsedown = \Mockery::mock('\Parsedown');
        $testContentFolder = __DIR__.'../../fixtures/';

        $this->contentService = new ContentService($mockedParsedown, $testContentFolder);

	}
	
	public function testFilenameAddsMd()
	{
        $name = "test";
		$this->contentService->createFileNameFromName($name);

	}

}