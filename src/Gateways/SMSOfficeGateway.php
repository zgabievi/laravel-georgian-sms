<?php

namespace Gabievi\SMS\Gateways;

use Gabievi\SMS\Contracts\SMSGateway;
use Gabievi\SMS\Exceptions\ActionNotAllowedException;

class SMSOfficeGateway extends Gateway implements SMSGateway
{
    /**
     * @var string
     */
    protected $api_url = 'http://smsoffice.ge/api';

    /**
     * @var string
     */
    protected $send_url;

    /**
     * @var string
     */
    protected $balance_url;

    /**
     * SMSOfficeGateway constructor.
     */
    public function __construct()
    {
        parent::__construct('smsoffice');

        $this->send_url = $this->api_url . '/v2/send';
        $this->balance_url = $this->api_url . '/getBalance';
    }

    /**
     * Merge provider parameters into additional params.
     *
     * @param array $params
     * @return array
     */
    public function getParams(array $params = [])
    {
        return array_merge($params, [
            'key' => $this->credentials['key'],
            'sender' => $this->credentials['brand'],
        ]);
    }

    /**
     * Generate url to perform some actions.
     *
     * @param $action
     * @param null $params
     * @return string
     * @throws ActionNotAllowedException
     */
    public function generateUrl($action, $params = null)
    {
        $query = $params ? $this->buildQuery($this->getParams($params)) : '';

        switch ($action) {
            case 'send':
                return $this->send_url . $query;

            case 'balance':
                return $this->balance_url . $query;

            default:
                throw new ActionNotAllowedException($action, 'SMSOffice');
        }
    }

    /**
     * Send sms using current gateway.
     *
     * @param $numbers
     * @param $message
     * @param array $params
     * @return array
     */
    public function send($numbers, $message, array $params = [])
    {
        $to = is_array($numbers) ? implode(',', $numbers) : $numbers;
        $reference = uniqid();

        $response = $this->getContent('send', array_merge([
            'destination' => $to,
            'content' => $message,
            'reference' => $reference,
        ], $params));

        if ($response !== null && $response['Success']) {
            return [
                'code' => (int)$response['result'],
                'reference' => $reference,
            ];
        }

        return ['code' => 500];
    }

    /**
     * Return balance left on current gateway provider.
     *
     * @return int
     */
    public function balance()
    {
        return (int)file_get_contents($this->generateUrl('balance'));
    }
}
