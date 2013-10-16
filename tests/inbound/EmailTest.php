<?php

use Camelcased\Postmark\Inbound\Email as Email;
use Camelcased\Postmark\Inbound\Attachment as Attachment;

class EmailTest extends PHPUnit_Framework_TestCase {
	public function testGetToField()
	{
		$email = new Email(array('to' => 'me@camelcased.com'));

		$this->assertEquals(
			$email->to(),
			'me@camelcased.com'
		);
	}

	public function testGetFromField()
	{
		$email = new Email(array('from' => 'you@camelcased.com'));

		$this->assertEquals(
			$email->from(),
			'you@camelcased.com'
		);
	}

	public function testGetReplyToField()
	{
		$email = new Email(array('replyTo' => 'you@camelcased.com'));

		$this->assertEquals(
			$email->replyTo(),
			'you@camelcased.com'
		);
	}

	public function testGetSubjectField()
	{
		$email = new Email(array('subject' => 'PHPUnit Test'));

		$this->assertEquals(
			$email->subject(),
			'PHPUnit Test'
		);
	}

	public function testGetBodyFieldNoHTML()
	{
		$email = new Email(array('body' => 'PHPUnit Testing is awesome'));

		$this->assertEquals(
			$email->body(),
			'PHPUnit Testing is awesome'
		);
	}

	public function testGetBodyFieldWithHTML()
	{
		$email = new Email(array('body' => '<h1>PHPUnit Testing is awesome</h1>'));

		$this->assertEquals(
			$email->body(),
			'<h1>PHPUnit Testing is awesome</h1>'
		);
	}

	public function testCheckForAttachments()
	{
		$email = new Email(array('Attachments' => array(0 => array('Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain'))));

		$this->assertEquals(
			$email->hasAttachments(),
			true
		);
	}

	public function testGetAttachmentsFieldOneAttachment()
	{
		$email = new Email(array('Attachments' => array(0 => array('Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain'))));

		$attachment = new Attachment(array('Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain'));

		$this->assertEquals(
			$email->attachments(),
			array(0 => $attachment)
		);
	}

	public function testGetAttachmentsFieldMultipleAttachments()
	{
		$email = new Email(array('Attachments' => array(0 => array('Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain'), 1 => array('Name' => 'Bye.txt', 'Content' => 'someBase64EncodedContentAgain', 'MIME' => 'text/plain'))));

		$attachment = new Attachment(array('Name' => 'Hello.txt', 'Content' => 'someBase64EncodedContent', 'MIME' => 'text/plain'));
		$attachment1 = new Attachment(array('Name' => 'Bye.txt', 'Content' => 'someBase64EncodedContentAgain', 'MIME' => 'text/plain'));

		$this->assertEquals(
			$email->attachments(),
			array(0 => $attachment, 1 => $attachment1)
		);
	}

	public function testBodyIsText()
	{
		$email = new Email(array('body' => 'PHPUnit Testing is awesome'));

		$this->assertEquals(
			$email->bodyIsText(),
			true
		);
	}

	public function testBodyIsTextWithHtml()
	{
		$email = new Email(array('body' => '<h1>PHPUnit Testing is awesome</h1>'));

		$this->assertEquals(
			$email->bodyIsText(),
			false
		);
	}

	public function testBodyIsHtml()
	{
		$email = new Email(array('body' => '<h1>PHPUnit Testing is awesome</h1>'));

		$this->assertEquals(
			$email->bodyIsHtml(),
			true
		);
	}

	public function testBodyIsHtmlWithText()
	{
		$email = new Email(array('body' => 'PHPUnit Testing is awesome'));

		$this->assertEquals(
			$email->bodyIsHtml(),
			false
		);
	}
}