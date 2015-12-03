<?php

namespace Gabievi\SMS;

use Illuminate\Support\ServiceProvider;

class SMSServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/sms.php' => config_path('sms.php'),
		]);
	}

	/**
	 * Register the application services.
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/config/sms.php', 'sms'
		);

		$this->app['sms'] = $this->app->share(
			function () {
				return new SMS();
			}
		);
	}
}
