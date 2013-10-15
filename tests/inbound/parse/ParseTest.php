<?php

use Camelcased\Postmark\Inbound\Parse\Parser;

class ParseTest extends PHPUnit_Framework_TestCase {
	public function testParserReturnsAnArrayOnEmptyJson()
	{
		$parser = new Parser('{}');

		$this->assertEquals(
			$parser->parse(),
			array()
		);
	}

	public function testParserReturnsProperArrayNoAttachments()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => ['sample.cc@emailDomain.com', 'another.cc@emailDomain.com']];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReutrnsTextBodyIfNoHtmlBodyGiven()
	{
		$parser = new Parser('{"TextBody": "Hello", "HtmlBody": "", "Subject": "Test", "To": "uhh", "From": "ugh", "ReplyTo": "me", "Cc": "\"Full name\" <sample.cc@emailDomain.com>"}');

		$this->assertEquals(
			$parser->parse(),
			['body' => 'Hello', 'subject' => 'Test', 'to' => 'uhh', 'replyTo' => 'me', 'from' => 'ugh', 'cc' => 'sample.cc@emailDomain.com']
		);
	}

	public function testParserReturnsProperArrayWithAttachments()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_attachments.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => ['sample.cc@emailDomain.com', 'another.cc@emailDomain.com'], 'Attachments' => [0 => ['Name' => 'Hello.txt', 'Content' => 'SGVsbG8gV29ybGQh', 'MIME' => 'text/plain']]];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsProperArrayWithMultipleAttachments()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_multiple_attachments.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => ['sample.cc@emailDomain.com', 'another.cc@emailDomain.com'], 'Attachments' => [0 => ['Name' => 'Hello.txt', 'Content' => 'SGVsbG8gV29ybGQh', 'MIME' => 'text/plain'], 1 => ['Name' => 'Bye.txt', 'Content' => 'QnllIFdvcmxk', 'MIME' => 'text/plain']]];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsFromAsReplyToIfNoReplyToGiven()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_no_reply_to.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUser@theirDomain.com', 'cc' => ['sample.cc@emailDomain.com', 'another.cc@emailDomain.com']];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsCcWithMoreThanOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' =>['sample.cc@emailDomain.com', 'another.cc@emailDomain.com']];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsCcWithOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_cc.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => 'sample.cc@emailDomain.com'];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsBccWithMoreThanOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_bcc.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'bcc' => ['sample.cc@emailDomain.com', 'another.cc@emailDomain.com']];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsBccWithOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_bcc_one.json');

		$parser = new Parser($input);

		$output = ['body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'bcc' => 'sample.cc@emailDomain.com'];

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}
}