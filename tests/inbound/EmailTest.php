<?php

use Camelcased\Postmark\Inbound\Email as Email;
use Camelcased\Postmark\Inbound\Attachment as Attachment;

class EmailTest extends PHPUnit_Framework_TestCase {
	public function testGetToField()
	{
		$email = new Email(['to' => 'me@camelcased.com']);

		$this->assertEquals(
			$email->to(),
			'me@camelcased.com'
		);
	}

	public function testGetFromField()
	{
		$email = new Email(['from' => 'you@camelcased.com']);

		$this->assertEquals(
			$email->from(),
			'you@camelcased.com'
		);
	}

	public function testGetReplyToField()
	{
		$email = new Email(['replyTo' => 'you@camelcased.com']);

		$this->assertEquals(
			$email->replyTo(),
			'you@camelcased.com'
		);
	}

	public function testGetSubjectField()
	{
		$email = new Email(['subject' => 'PHPUnit Test']);

		$this->assertEquals(
			$email->subject(),
			'PHPUnit Test'
		);
	}

	public function testGetBodyFieldNoHTML()
	{
		$email = new Email(['body' => 'PHPUnit Testing is awesome']);

		$this->assertEquals(
			$email->body(),
			'PHPUnit Testing is awesome'
		);
	}

	public function testGetBodyFieldWithHTML()
	{
		$email = new Email(['body' => '<h1>PHPUnit Testing is awesome</h1>']);

		$this->assertEquals(
			$email->body(),
			'<h1>PHPUnit Testing is awesome</h1>'
		);
	}

	public function testCheckForAttachments()
	{
		$email = new Email(['Attachments' => [0 => ['Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain']]]);

		$this->assertEquals(
			$email->hasAttachments(),
			true
		);
	}

	public function testGetAttachmentsFieldOneAttachment()
	{
		$email = new Email(['Attachments' => [0 => ['Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain']]]);

		$attachment = new Attachment(['Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain']);

		$this->assertEquals(
			$email->attachments(),
			[0 => $attachment]
		);
	}

	public function testGetAttachmentsFieldMultipleAttachments()
	{
		$email = new Email(['Attachments' => [0 => ['Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain'], 1 => ['Name' => 'Bye.txt', 'Content' => 'someBase64EncodedContentAgain', 'MIME' => 'text/plain']]]);

		$attachment = new Attachment(['Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain']);
		$attachment1 = new Attachment(['Name' => 'Bye.txt', 'Content' => 'someBase64EncodedContentAgain', 'MIME' => 'text/plain']);

		$this->assertEquals(
			$email->attachments(),
			[0 => $attachment, 1 => $attachment1]
		);
	}

	public function testBodyIsText()
	{
		$email = new Email(['body' => 'PHPUnit Testing is awesome']);

		$this->assertEquals(
			$email->bodyIsText(),
			true
		);
	}

	public function testBodyIsHtml()
	{
		$email = new Email(['body' => '<h1>PHPUnit Testing is awesome</h1>']);

		$this->assertEquals(
			$email->bodyIsHtml(),
			true
		);
	}
}