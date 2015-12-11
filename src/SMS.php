<?php

namespace Gabievi\SMS;

use Gabievi\SMS\Contracts\SMSOffice;
use Gabievi\SMS\Contracts\MSG;

class SMS
{
	/**
	 * @var
	 */
	public $config;

	/**
	 * SMS constructor.
	 */
	public function __construct()
	{
		$this->config = config('sms.default') == 'msg' ? config('sms.providers.msg') : config('sms.providers.smsoffice');
	}

	/**
	 * @param $receiver
	 * @param $message
	 *
	 * @return mixed
	 */
	public function send($receiver, $message)
	{
		if (config('sms.default') == 'msg') {
			$url = 'http://msg.ge/bi/sendsms.php?username=' . $this->config['username'] . '&password=' . $this->config['password'] . '&client_id=' . $this->config['client_id'] . '&service_id=' . $this->config['service_id'] . '&to=' . $receiver . '&text=' . urlencode($message);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			$data = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpcode >= 200 && $httpcode < 300) {
				$response['success'] = true;
				$response['code'] = explode('-', $data)[0];
				$response['message_id'] = (int)explode('-', $data)[1];
			} else {
				$response['false'] = true;
			}
		} else {
			$reference = uniqid();
			$method = strlen($receiver) >= 4096 ? 'POST' : 'GET';
			$url = 'http://smsoffice.ge/api/send.aspx';

			$parameters = [
				'key' => $this->config['key'],
				'destination' => $receiver,
				'sender' => $this->config['sender'],
				'content' => urlencode($message),
				'reference' => $reference,
			];

			$ch = curl_init();

			if ($method == 'POST') {
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
			} else {
				$parameterString = '';

				if (is_array($parameters) && count($parameters) != 0) {
					$parameterString = '?' . http_build_query($parameters);
				}

				curl_setopt($ch, CURLOPT_URL, $url . $parameterString);
			}

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			$data = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpcode >= 200 && $httpcode < 300) {
				$response['success'] = true;
				$response['code'] = (int)$data;
				$response['reference'] = $reference;
			} else {
				$response['false'] = true;
			}
		}

		return $response;
	}

	/**
	 * @param $message_id
	 *
	 * @return bool
	 */
	public function check($message_id)
	{
		if (config('sms.default') == 'msg') {
			$url = 'http://msg.ge/bi/track.php?username=' . $this->config['username'] . '&password=' . $this->config['password'] . '&client_id=' . $this->config['client_id'] . '&service_id=' . $this->config['service_id'] . '&message_id=' . $message_id;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			$data = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			if ($httpcode >= 200 && $httpcode < 300) {
				$response['success'] = true;
				$response['code'] = (int)$data;
			} else {
				$response['false'] = true;
			}

			return $response;
		} else {
			return false;
		}
	}

	/**
	 * @return string
	 */
	public function getBalance()
	{
		if (config('sms.default') == 'smsoffice') {
			return file_get_contents('http://smsoffice.ge/api/getBalance?key=' . $this->config['key']);
		} else {
			return false;
		}
	}
}