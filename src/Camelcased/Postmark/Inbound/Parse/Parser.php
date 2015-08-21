<?php namespace Camelcased\Postmark\Inbound\Parse;

class Parser {

	/**
	 * @var array
	 */
	protected $inbound;


	/**
	 * @var array
	 */
	protected $output;

	public function __construct($json)
	{
		if (empty($json))
		{
			// Well we weren't given anything to parse
			$this->inbound = [];
		} else {
			// Check to see if the given JSON is already an array
			if (is_array($json))
			{
				$this->inbound = $json;
			} else {
				// Convert the string to an array
				$this->inbound = $this->jsonToArray($json);
			}
		}
	}

	/**
	 * Parse the email from JSON to a formatted array
	 *
	 * @return array
	 */
	public function parse()
	{
		if ($this->inbound == [])
		{
			// Well that was short lived.
			return [];
		}

		// Set the Body field based on whether it is HTMl or good old fashioned plain text
		if (!$this->htmlBody())
		{
			$this->output['body'] = $this->inbound["TextBody"];
		} else {
			$this->output['body'] = $this->inbound["HtmlBody"];
		}

		// Easy stuff to parse. Self explainatory.
		$this->output['subject'] = $this->inbound["Subject"];
		$this->output['to'] = $this->inbound["To"];
		$this->output['replyTo'] = $this->replyTo();
		$this->output['from'] = $this->inbound["From"];

		// Set cc field if the email has any CC's set
		if ($this->has('Cc'))
		{
			$this->output['cc'] = $this->carbon('Cc');
		}

		// Set bcc field if the email has any BCC's set
		if ($this->has('Bcc'))
		{
			$this->output['bcc'] = $this->carbon('Bcc');
		}

		// Does the email have any attachments
		if ($this->has('Attachments'))
		{
			$this->output['Attachments'] = [];
			$i = 0;

			// Loop through each of the attachments and convert it to an array for later use
			foreach($this->inbound["Attachments"] as $attachment)
			{
				$this->output['Attachments'][$i] = ["Name" => $attachment["Name"], "Content" => $attachment["Content"], "MIME" => $attachment["ContentType"]];
				$i++;
			}
		}

		// Return the parsed email
		return $this->output;
	}

	/**
	 * Converts the given JSON string to an array
	 *
	 * @param string $json
	 * @return array
	 */
	private function jsonToArray($json)
	{
		$source = json_decode($json, true);

		if (json_last_error() == JSON_ERROR_NONE)
		{
			return $source;
		}

		return [];
	}

	/**
	 * Checks if there is an html body
	 *
	 * @return boolean
	 */
	private function htmlBody()
	{
		return ($this->inbound["HtmlBody"])  != null || ($this->inbound["HtmlBody"])  != "" ? true : false;
	}

	/**
	 * Sets the correct ReplyTo field based on whether the email had a ReplyTo field or not.
	 *
	 * @return string
	 */
	private function replyTo()
	{
		// No ReplyTo field given from the email
		if (empty($this->inbound["ReplyTo"]) || $this->inbound["ReplyTo"] == '')
		{
			// So we set the ReplyTo field as the address given in the From field
			return $this->inbound["From"];
		}

		return $this->inbound["ReplyTo"];
	}

	/**
	 * Extracts the email from the input. Ex: "Full name" <sample.cc@emailDomain.com>
	 *
	 * @param string $input
	 * @return string
	 */
	private function extractEmail($input)
	{
		if (preg_match('~<(.*?)>~', $input, $output) == 1)
		{
			return $output[1];
		}
		return $input;
	}

	/**
	 * Converts BCC and CC fields into an array or string if needed
	 *
	 * @param string $field
	 * @return mixed
	 */
	private function carbon($field)
	{
		$carbons = explode(',', $this->inbound[$field]);

		// Is there more than one email in the given carbon field
		if ($carbons != [$this->inbound[$field]])
		{
			$final = [];
			foreach ($carbons as $carbon) {
				array_push($final, $this->extractEmail($carbon));
			}

			return $final;
		}

		return $this->extractEmail($this->inbound[$field]);
	}

	/**
	 * Simple helper function to check if the email has the given field
	 *
	 * @param string $key
	 * @return boolean
	 */
	private function has($key)
	{
		if (array_key_exists($key, $this->inbound)) {
			return count($this->inbound[$key]) > 0 ? true : false;
		} else {
			return false;
		}
	}
}
