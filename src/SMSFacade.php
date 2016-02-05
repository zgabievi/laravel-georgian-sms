<?php

namespace Gabievi\SMS;

use Illuminate\Support\Facades\Facade;

class SMSFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'sms';
	}
}