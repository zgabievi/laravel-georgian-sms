<?php

namespace Gabievi\SMS;

use Gabievi\SMS\Exceptions\ActionNotAllowedException;
use Gabievi\SMS\Exceptions\GatewayNotSupportedException;

class SMS
{
    /**
     * @type mixed
     */
    protected $gateway;

    /**
     * @var string
     */
    protected $provider;

    /**
     * @var array
     */
    protected $mapProviders = [
        'magti' => 'Magti',
        'smsoffice' => 'SMSOffice',
        'smsco' => 'SMSCo',
    ];

    /**
     * SMS constructor.
     */
    public function __construct()
    {
        $provider = config('sms.default');

        if (!array_key_exists($this->provider, $this->mapProviders)) {
            throw new GatewayNotSupportedException($this->provider);
        }

        $this->provider = $this->mapProviders[$provider];
        $gateway = "\Gabievi\SMS\Gateways\{$this->provider}Gateway";

        $this->gateway = new $gateway();
    }

    /**
     * Send message using default gateway.
     *
     * @param $numbers
     * @param $message
     * @param array $params
     * @return mixed
     * @throws ActionNotAllowedException
     */
    public function send($numbers, $message, array $params = [])
    {
        if (!method_exists($this->gateway, 'send')) {
            throw new ActionNotAllowedException('send', $this->provider);
        }

        return $this->gateway->send($numbers, $message, $params);
    }

    /**
     * Send scheduled message.
     *
     * @param $numbers
     * @param $message
     * @param $datetime
     * @return mixed
     * @throws ActionNotAllowedException
     */
    public function schedule($numbers, $message, $datetime)
    {
        if (!method_exists($this->gateway, 'schedule')) {
            throw new ActionNotAllowedException('schedule', $this->provider);
        }

        return $this->gateway->schedule($numbers, $message, $datetime);
    }

    /**
     * Get status of already send message.
     *
     * @param $msg_id
     * @return mixed
     * @throws ActionNotAllowedException
     */
    public function status($msg_id)
    {
        if (!method_exists($this->gateway, 'status')) {
            throw new ActionNotAllowedException('status', $this->provider);
        }

        return $this->gateway->status($msg_id);
    }

    /**
     * Get balance left on users account.
     *
     * @return mixed
     * @throws ActionNotAllowedException
     */
    public function balance()
    {
        if (!method_exists($this->gateway, 'balance')) {
            throw new ActionNotAllowedException('balance', $this->provider);
        }

        return $this->gateway->balance();
    }
}
