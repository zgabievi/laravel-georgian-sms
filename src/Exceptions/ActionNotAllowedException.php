<?php

namespace Gabievi\SMS\Exceptions;

class ActionNotAllowedException extends \Exception
{
    /**
     * ActionNotAllowedException constructor.
     * @param string $action
     * @param string $gateway
     */
	public function __construct($action, $gateway)
	{
		parent::__construct("There is no such action '{$action}' on {$gateway} gateway");
	}
}
