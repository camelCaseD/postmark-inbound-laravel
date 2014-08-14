<?php namespace Camelcased\Postmark\Inbound;

use Camelcased\Postmark\Inbound\Attachment as Attachment;

class Email {
	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var array
	 */
	protected $attachments = array();

	public function __construct(array $data)
	{
		$this->data = $data;

		// Does the email contain attachments?
		if ($this->hasAttachments())
		{
			// Then setup the array with Attachment objects
			$this->setAttachments();
		}
	}

	/**
	 * Maps undefined functions to the correct field in the email
	 *
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{
		return $this->data[$name];
	}

	/**
	 * Check for if the email has any attachments
	 *
	 * @return boolean
	 */
	public function hasAttachments()
	{
		if (array_key_exists('Attachments', $this->data)) {
			return count($this->data["Attachments"]) > 0 ? true : false;
		}

		return false;
	}

	/**
	 * Is the body in the email plain text?
	 *
	 * @return boolean
	 */
	public function bodyIsText()
	{
		if ($this->data['body'] == strip_tags($this->data['body']))
		{
			return true;
		}

		return false;
	}

	/**
	 * Is the body in the email HTML?
	 *
	 * @return boolean
	 */
	public function bodyIsHtml()
	{
		if ($this->data['body'] != strip_tags($this->data['body']))
		{
			return true;
		}

		return false;
	}

	/**
	 * Return the array of Attachment objects
	 *
	 * @return array
	 */
	public function attachments()
	{
		return $this->attachments;
	}

	/**
	 * Loops through the attachments and creates an Attachment object for each attachemnt then pushes each object into an array.
	 *
	 * @return void
	 */
	private function setAttachments()
	{
		foreach ($this->data['Attachments'] as $attachment)
		{
			array_push($this->attachments, new Attachment($attachment));
		}
	}
}
