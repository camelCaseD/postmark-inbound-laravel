<?php

use Camelcased\Postmark\Inbound\Parse\Parser;

class ParseTest extends PHPUnit_Framework_TestCase {
	public function testParserReturnsAnEmptyArray()
	{
		$parser = new Parser('{}');

		$this->assertEquals(
			$parser->parse(),
			array()
		);

		$emptyParser = new Parser(false);

		$this->assertEquals(
			$emptyParser->parse(),
			array()
		);

		$invalidParser = new Parser('{"invalid""syntax"}');

		$this->assertEquals(
			$invalidParser->parse(),
			array()
		);
	}

	public function testParserReturnsProperArrayNoAttachments()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => array('sample.cc@emailDomain.com', 'another.cc@emailDomain.com'));

		$this->assertEquals(
			$parser->parse(),
			$output
		);

		$arrayInput = array (
		  'From' => 'myUser@theirDomain.com',
		  'FromFull' => 
		  array (
		    'Email' => 'myUser@theirDomain.com',
		    'Name' => 'John Doe',
		  ),
		  'To' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com',
		  'ToFull' => 
		  array (
		    0 => 
		    array (
		      'Email' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com',
		      'Name' => '',
		    ),
		  ),
		  'Cc' => '"Full name" <sample.cc@emailDomain.com>, "Another Cc" <another.cc@emailDomain.com>',
		  'CcFull' => 
		  array (
		    0 => 
		    array (
		      'Email' => 'sample.cc@emailDomain.com',
		      'Name' => 'Full name',
		    ),
		    1 => 
		    array (
		      'Email' => 'another.cc@emailDomain.com',
		      'Name' => 'Another Cc',
		    ),
		  ),
		  'ReplyTo' => 'myUsersReplyAddress@theirDomain.com',
		  'Subject' => 'This is an inbound message',
		  'MessageID' => '22c74902-a0c1-4511-804f2-341342852c90',
		  'Date' => 'Thu, 5 Apr 2012 16:59:01 +0200',
		  'MailboxHash' => 'ahoy',
		  'TextBody' => 'Hello',
		  'HtmlBody' => '<h1>Hello</h1>',
		  'Tag' => '',
		  'Headers' => 
		  array (
		    0 => 
		    array (
		      'Name' => 'X-Spam-Checker-Version',
		      'Value' => 'SpamAssassin 3.3.1 (2010-03-16) onrs-ord-pm-inbound1.wildbit.com',
		    ),
		    1 => 
		    array (
		      'Name' => 'X-Spam-Status',
		      'Value' => 'No',
		    ),
		    2 => 
		    array (
		      'Name' => 'X-Spam-Score',
		      'Value' => '-0.1',
		    ),
		    3 => 
		    array (
		      'Name' => 'X-Spam-Tests',
		      'Value' => 'DKIM_SIGNED,DKIM_VALID,DKIM_VALID_AU,SPF_PASS',
		    ),
		    4 => 
		    array (
		      'Name' => 'Received-SPF',
		      'Value' => 'Pass (sender SPF authorized) identity=mailfrom; client-ip=209.85.160.180; helo=mail-gy0-f180.google.com; envelope-from=myUser@theirDomain.com; receiver=451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com',
		    ),
		    5 => 
		    array (
		      'Name' => 'DKIM-Signature',
		      'Value' => 'v=1; a=rsa-sha256; c=relaxed/relaxed;        d=wildbit.com; s=google;        h=mime-version:reply-to:date:message-id:subject:from:to:cc         :content-type;        bh=cYr/+oQiklaYbBJOQU3CdAnyhCTuvemrU36WT7cPNt0=;        b=QsegXXbTbC4CMirl7A3VjDHyXbEsbCUTPL5vEHa7hNkkUTxXOK+dQA0JwgBHq5C+1u         iuAJMz+SNBoTqEDqte2ckDvG2SeFR+Edip10p80TFGLp5RucaYvkwJTyuwsA7xd78NKT         Q9ou6L1hgy/MbKChnp2kxHOtYNOrrszY3JfQM=',
		    ),
		    6 => 
		    array (
		      'Name' => 'MIME-Version',
		      'Value' => '1.0',
		    ),
		    7 => 
		    array (
		      'Name' => 'Message-ID',
		      'Value' => '<CAGXpo2WKfxHWZ5UFYCR3H_J9SNMG+5AXUovfEFL6DjWBJSyZaA@mail.gmail.com>',
		    ),
		  ),
		);

		$arrayParser = new Parser($arrayInput);

		$this->assertEquals(
			$arrayParser->parse(),
			$output
		);
	}

	public function testParserReutrnsTextBodyIfNoHtmlBodyGiven()
	{
		$parser = new Parser('{"TextBody": "Hello", "HtmlBody": "", "Subject": "Test", "To": "uhh", "From": "ugh", "ReplyTo": "me", "Cc": "\"Full name\" <sample.cc@emailDomain.com>"}');

		$this->assertEquals(
			$parser->parse(),
			array('body' => 'Hello', 'subject' => 'Test', 'to' => 'uhh', 'replyTo' => 'me', 'from' => 'ugh', 'cc' => 'sample.cc@emailDomain.com')
		);
	}

	public function testParserReturnsProperArrayWithAttachments()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_attachments.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => array('sample.cc@emailDomain.com', 'another.cc@emailDomain.com'), 'Attachments' => array(0 => array('Name' => 'Hello.txt', 'Content' => 'SGVsbG8gV29ybGQh', 'MIME' => 'text/plain')));

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsProperArrayWithMultipleAttachments()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_multiple_attachments.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => array('sample.cc@emailDomain.com', 'another.cc@emailDomain.com'), 'Attachments' => array(0 => array('Name' => 'Hello.txt', 'Content' => 'SGVsbG8gV29ybGQh', 'MIME' => 'text/plain'), 1 => array('Name' => 'Bye.txt', 'Content' => 'QnllIFdvcmxk', 'MIME' => 'text/plain')));

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsFromAsReplyToIfNoReplyToGiven()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_no_reply_to.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUser@theirDomain.com', 'cc' => array('sample.cc@emailDomain.com', 'another.cc@emailDomain.com'));

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsCcWithMoreThanOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => array('sample.cc@emailDomain.com', 'another.cc@emailDomain.com'));

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsCcWithOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_cc.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'cc' => 'sample.cc@emailDomain.com');

		$this->assertEquals(
			$parser->parse(),
			$output
		);

		$emailParser = new Parser(file_get_contents(__DIR__ . '/stubs/inbound_cc_just_email.json'));

		$this->assertEquals(
			$emailParser->parse(),
			$output
		);
	}

	public function testParserReturnsBccWithMoreThanOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_bcc.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'bcc' => array('sample.cc@emailDomain.com', 'another.cc@emailDomain.com'));

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}

	public function testParserReturnsBccWithOne()
	{
		$input = file_get_contents(__DIR__ . '/stubs/inbound_bcc_one.json');

		$parser = new Parser($input);

		$output = array('body' => '<h1>Hello</h1>', 'subject' => 'This is an inbound message', 'to' => '451d9b70cf9364d23ff6f9d51d870251569e+ahoy@inbound.postmarkapp.com', 'from' => 'myUser@theirDomain.com', 'replyTo' => 'myUsersReplyAddress@theirDomain.com', 'bcc' => 'sample.cc@emailDomain.com');

		$this->assertEquals(
			$parser->parse(),
			$output
		);
	}
}