<?php namespace Camelcased\Postmark;

use Illuminate\Support\Facades\Facade;

class Inbound extends Facade {
	protected static function getFacadeAccessor() { return 'inbound'; }
}