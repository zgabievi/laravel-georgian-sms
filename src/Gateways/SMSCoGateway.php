<?php

namespace Gabievi\SMS\Gateways;

use Gabievi\SMS\Contracts\SMSGateway;
use Gabievi\SMS\Exceptions\ActionNotAllowedException;

class SMSCoGateway extends Gateway implements SMSGateway
{
    /**
     * @var string
     */
    protected $api_url = 'http://smsco.ge/api';

    /**
     * @var string
     */
    protected $send_url;

    /**
     * @var string
     */
    protected $status_url;

    /**
     * SMSOfficeGateway constructor.
     */
    public function __construct()
    {
        parent::__construct('smsco');

        $this->send_url = $this->api_url . '/sendsms.php';
        $this->status_url = $this->api_url . '/getstatus.php';
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
            'username' => $this->credentials['username'],
            'password' => $this->credentials['password'],
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

            case 'status':
                return $this->status_url . $query;

            default:
                throw new ActionNotAllowedException($action, 'Magti');
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

        $response = $this->cURL('send', array_merge([
            'recipient' => $to,
            'message' => urlencode($message),
            'balance' => true,
        ], $params));

        if ($response['info'] >= 200 && $response['info'] < 300) {
            $result = explode(' ', $response['result']);

            return [
                'code' => $result[0] == 'OK' ? 0 : $result,
                'msg_id' => $result[0] == 'OK' ? $result[2] : null,
            ];
        }

        return ['code' => 500];
    }

    /**
     * Return status of sent message.
     *
     * @param int $msg_id
     * @return array
     */
    public function status($msg_id)
    {
        return $this->cURL('status', [
            'mes_id' => $msg_id
        ]);
    }

    /**
     * Schedule messages to be sent later.
     *
     * @param $numbers
     * @param $message
     * @param $datetime
     * @return array
     */
    public function schedule($numbers, $message, $datetime)
    {
        return $this->send($numbers, $message, [
            'schedule' => date('Y-m-d H:i:s', strtotime($datetime)),
        ]);
    }
}
