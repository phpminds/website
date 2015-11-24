<?php

namespace App\Tests\Service;

use App\Service\ContentService;

class ContentServiceTest extends \PHPUnit_Framework_TestCase
{

    private $contentService;

	private $contentFolder;

	public function setUp()
	{
        $mockedParsedown = \Mockery::mock('\Parsedown["text"]');
        $this->contentFolder = __DIR__.'/fixtures/';

        $this->contentService = new ContentService($mockedParsedown, $this->contentFolder);

	}
	
	public function testFilenameAddsMd()
	{
        $name = "test";

		$actual = $this->contentService->createFileNameFromName($name);
		$this->assertEquals($this->contentFolder. $name.".md",$actual);

	}

	public function testGetContentByNameReturnsEmptyString_WhenNullName()
	{

		$actual = $this->contentService->getContentByName(null);
		$this->assertEquals("",$actual);
	}

	/**
	 * Integration test against parsedown
	 */
	public function testGetContentByNameReturnsString_WithAppropriateName()
	{

		$actual = $this->contentService->getContentByName('testContent');
		$this->assertEquals("<p>Something</p>",$actual);

	}
}