<?php

use Camelcased\Postmark\Inbound\Attachment as Attachment;

class AttachmentTest extends PHPUnit_Framework_TestCase {
	public function testGetNameField()
	{
		$attachment = new Attachment(array('Name' => 'Hello.txt'));

		$this->assertEquals(
			$attachment->Name(),
			'Hello.txt'
		);
	}

	public function testGetContentField()
	{
		$base64 = new Attachment(array('Content' => 'aGVsbG8='));

		$this->assertEquals(
			$base64->Content(),
			'aGVsbG8='
		);

		$this->assertEquals(
			$base64->decodedContent(),
			'hello'
		);
	}

	public function testGetMIMEField()
	{
		$attachment = new Attachment(array('MIME' => 'text/plain'));

		$this->assertEquals(
			$attachment->MIME(),
			'text/plain'
		);

		$this->assertEquals(
			$attachment->Type(),
			'text/plain'
		);
	}
}