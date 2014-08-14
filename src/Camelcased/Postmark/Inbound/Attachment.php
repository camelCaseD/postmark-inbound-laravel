<?php namespace Camelcased\Postmark\Inbound;

class Attachment {
	/**
	 * @var array
	 */
	protected $attachment;

	public function __construct(array $attachment)
	{
		$this->attachment = $attachment;
	}

	/**
	 * Maps any undefined functions to the correct field within the attachment
	 *
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{
		return $this->attachment[$name];
	}

	/**
	 * Returns the attachments MIME type. Ex: image/jpeg
	 *
	 * @return string
	 */
	public function Type()
	{
		return $this->attachment['MIME'];
	}

	/**
	 * Returns the content of the attachment in all of it's raw glory
	 *
	 * @return mixed
	 */
	public function DecodedContent()
	{
		return base64_decode($this->attachment['Content']);
	}
}
