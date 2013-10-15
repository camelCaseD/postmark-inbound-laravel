<?php namespace Camelcased\Postmark\Inbound;

class Attachment {
	protected $attachment;

	public function __construct($attachment)
	{
		$this->attachment = $attachment;
	}

	public function __call($name, $arguments)
	{
		return $this->attachment[$name];
	}

	public function Type()
	{
		return $this->attachment['MIME'];
	}

	public function decodedContent()
	{
		return base64_decode($this->attachment['Content']);
	}
}