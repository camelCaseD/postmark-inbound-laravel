<?php namespace Camelcased\Postmark\Inbound;

use Camelcased\Postmark\Inbound\Attachment as Attachment;

class Email {
	protected $data;

	protected $attachments = array();

	public function __construct(array $data)
	{
		$this->data = $data;

		if ($this->hasAttachments())
		{
			$this->setAttachments();
		}
	}

	public function __call($name, $arguments)
	{
		return $this->data[$name];
	}

	public function hasAttachments()
	{
		if (array_key_exists('Attachments', $this->data)) {
			return count($this->data["Attachments"]) > 0 ? true : false;
		} 

		return false;
	}

	public function bodyIsText()
	{
		if ($this->data['body'] == strip_tags($this->data['body']))
		{
			return true;
		}

		return false;
	}

	public function bodyIsHtml()
	{
		if ($this->data['body'] != strip_tags($this->data['body']))
		{
			return true;
		}

		return false;
	}

	public function attachments()
	{
		return $this->attachments;
	}

	private function setAttachments()
	{
		foreach ($this->data['Attachments'] as $attachment)
		{
			array_push($this->attachments, new Attachment($attachment));
		}
	}
}