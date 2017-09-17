<?php

namespace Gabievi\SMS\Exceptions;

class GatewayNotSupportedException extends \Exception
{
    /**
     * GatewayNotSupportedException constructor.
     * @param string $gateway
     */
	public function __construct($gateway)
	{
		parent::__construct("Gateway named '{$gateway}' is not supported");
	}
}
