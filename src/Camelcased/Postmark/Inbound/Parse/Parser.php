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
			$this->output['cc'] = $this->cc();
		}

		if ($this->has('Bcc'))
		{
			$this->output['bcc'] = $this->bcc();
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
		} else {
			return $input;
		}
	}

	private function cc()
	{
		$ccs = explode(',', $this->inbound['Cc']);
		if ($ccs != array($this->inbound['Cc']))
		{
			$final = array();
			foreach ($ccs as $cc) {
				array_push($final, $this->extractEmail($cc));
			}
			return $final;
		} else {
			return $this->extractEmail($this->inbound['Cc']);
		}
	}

	private function bcc()
	{
		$bccs = explode(',', $this->inbound['Bcc']);
		if ($bccs != array($this->inbound['Bcc']))
		{
			$final = array();
			foreach ($bccs as $bcc) {
				array_push($final, $this->extractEmail($bcc));
			}
			return $final;
		} else {
			return $this->extractEmail($this->inbound['Bcc']);
		}
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