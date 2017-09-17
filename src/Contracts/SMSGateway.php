<?php

namespace Gabievi\SMS\Contracts;

interface SMSGateway
{
    public function getParams(array $params = []);

    public function generateUrl($action, $params = null);

    public function send($numbers, $message, array $params = []);
}
