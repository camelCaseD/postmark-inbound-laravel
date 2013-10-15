<?php namespace Camelcased\Postmark;

use Camelcased\Postmark\Inbound\Parse\Parser;
use Camelcased\Postmark\Inbound\Email;
use Illuminate\Support\ServiceProvider;

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
		$this->app['postmarkEmail'] = $this->app->share(function($app)
		{
			$parser = new Parser($app["Input"]->get());
			return new Email($parser->parse());
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}