<?php namespace Camelcased\Postmark;

use Illuminate\Support\Facades\Facade;

class PostmarkEmail extends Facade {
	protected static function getFacadeAccessor() { return 'postmarkEmail'; }
}