<?php

namespace Gabievi\SMS\Gateways;

use Gabievi\SMS\Contracts\SMSGateway;
use Gabievi\SMS\Exceptions\ActionNotAllowedException;

class MagtiGateway extends Gateway implements SMSGateway
{
    /**
     * @var string
     */
    protected $api_url = 'http://msg.ge/bi';

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
        parent::__construct('magti');

        $this->send_url = $this->api_url . '/sendsms.php';
        $this->status_url = $this->api_url . '/track.php';
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
            'client_id' => $this->credentials['client_id'],
            'service_id' => $this->credentials['service_id'],
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
        $query = $params ? $this->buildQuery($params) : '';

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
            'to' => $to,
            'message' => urlencode($message),
        ], $params));

        if ($response['info'] >= 200 && $response['info'] < 300) {
            $result = explode('-', $response['result']);

            return [
                'code' => $result[0],
                'msg_id' => (int)$result[1],
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
            'message_id' => $msg_id
        ]);
    }
}
