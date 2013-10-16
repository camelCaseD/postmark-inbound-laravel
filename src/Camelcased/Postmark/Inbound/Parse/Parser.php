<?php namespace Camelcased\Postmark\Inbound\Parse;

class Parser {
	private $inbound;

	private $output = array();

	public function __construct($json)
	{
		if (empty($json))
		{
			$this->inbound = null;
		} else {
			if (is_array($json))
			{
				$this->inbound = $json;
			} else {
				$this->inbound = $this->jsonToArray($json);
			}
		}
	}

	public function parse()
	{
		if ($this->inbound == null)
		{
			return array();
		}

		if (!$this->htmlBody())
		{
			$this->output['body'] = $this->inbound["TextBody"];
		} else {
			$this->output['body'] = $this->inbound["HtmlBody"];
		}

		$this->output['subject'] = $this->inbound["Subject"];
		$this->output['to'] = $this->inbound["To"];
		$this->output['replyTo'] = $this->replyTo();
		$this->output['from'] = $this->inbound["From"];

		if ($this->has('Cc'))
		{
			$this->output['cc'] = $this->carbon('Cc');
		}

		if ($this->has('Bcc'))
		{
			$this->output['bcc'] = $this->carbon('Bcc');
		}

		if ($this->has('Attachments'))
		{
			$this->output['Attachments'] = array();
			$i = 0;
			foreach($this->inbound["Attachments"] as $attachment)
			{
				$this->output['Attachments'][$i] = array("Name" => $attachment["Name"], "Content" => $attachment["Content"], "MIME" => $attachment["ContentType"]);
				$i++;
			}
		}

		return $this->output;
	}

	private function jsonToArray($json)
	{
		$source = json_decode($json, true);

		if (json_last_error() == JSON_ERROR_NONE)
		{
			return $source;
		}

		return null;
	}

	private function htmlBody()
	{
		return ($this->inbound["HtmlBody"])  != null || ($this->inbound["HtmlBody"])  != "" ? true : false;
	}

	private function replyTo()
	{
		if (empty($this->inbound["ReplyTo"]) || $this->inbound["ReplyTo"] == '')
		{
			return $this->inbound["From"];
		}

		return $this->inbound["ReplyTo"];
	}

	private function extractEmail($input)
	{
		if (preg_match('~<(.*?)>~', $input, $output) == 1)
		{
			return $output[1];
		}
		return $input;
	}

	private function carbon($field)
	{
		$carbons = explode(',', $this->inbound[$field]);

		// Is there more than one
		if ($carbons != array($this->inbound[$field]))
		{
			$final = array();
			foreach ($carbons as $carbon) {
				array_push($final, $this->extractEmail($carbon));
			}

			return $final;
		}

		return $this->extractEmail($this->inbound[$field]);
	}

	private function has($key)
	{
		if (array_key_exists($key, $this->inbound)) {
			return count($this->inbound[$key]) > 0 ? true : false;
		} else {
			return false;
		}
	}
}