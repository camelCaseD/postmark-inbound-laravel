<?php namespace Camelcased\Postmark;

use Camelcased\Postmark\Inbound\Parse\Parser;
use Camelcased\Postmark\Inbound\Email;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Input;

class PostmarkServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// Share method removed and replaced with singleton to allow laravel 5.4+ to work correctly without throwing a share() exception as the share() method was removed in laravel 5.4
		$this->app->singleton('postmarkEmail', function ($app) {
		    $parser = new Parser(Input::get()); // Create an instance of the parser with the given input
		    return new Email($parser->parse()); // Returns an instance of the given email as an Email object
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
