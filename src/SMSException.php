<?php

namespace Gabievi\SMS;

class SMSException extends \Exception
{

	/**
	 * SupportedLocalesNotDefined constructor.
	 *
	 * @param string $method
	 */
	public function __construct($method)
	{
		parent::__construct(ucfirst($method) . ' isn\'t supported for default provider: ' . strtoupper(config('sms.default')) . '!');
	}
}