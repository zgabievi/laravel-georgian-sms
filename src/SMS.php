<?php

namespace Gabievi\SMS;

use \Gabievi\SMS\SMSException;

class SMS
{

	/**
	 * Default SMS provider
	 * @type mixed
	 */
	protected $provider;

	/**
	 * Credentials of chosen SMS provider
	 * @type mixed
	 */
	protected $credentials;

	/**
	 * SMS constructor.
	 */
	public function __construct()
	{
		$this->provider = config('sms.default');
		$this->credentials = config('sms.providers')[$this->provider];
	}

	/**
	 * Generate URL of provider \w purpose
	 *
	 * @param string $purpose
	 * @param array $query
	 *
	 * @return mixed
	 */
	private function GenerateURL($purpose, $query = '')
	{
		$provider_url = [];

		// Generate provider urls
		switch ($this->provider) {
			case 'magti':
				$provider_url = [
					'send' => 'http://msg.ge/bi/sendsms.php',
					'status' => 'http://msg.ge/bi/track.php',
				];
				break;

			case 'smsoffice':
				$provider_url = [
					'send' => 'http://smsoffice.ge/api/send.aspx',
					'balance' => 'http://smsoffice.ge/api/getBalance',
				];
				break;

			case 'smsco':
				$provider_url = [
					'send' => 'http://smsco.ge/api/sendsms.php',
					'status' => 'http://smsco.ge/api/getstatus.php',
				];
				break;
		}

		return $provider_url[$purpose] != false
			? $provider_url[$purpose] . $query
			: false;
	}

	/**
	 * Generate http query for provider
	 *
	 * @param array $additional_params
	 *
	 * @return string
	 */
	private function BuildQuery($additional_params)
	{
		return '?' . http_build_query($this->GetParams($additional_params));
	}

	/**
	 * Generate params array for provider
	 *
	 * @param array $additional_params
	 *
	 * @return string
	 */
	private function GetParams($additional_params)
	{
		$provider_params = [];

		// Generate provider params
		switch ($this->provider) {
			case 'magti':
				$provider_params = [
					'username' => $this->credentials['username'],
					'password' => $this->credentials['password'],
					'client_id' => $this->credentials['client_id'],
					'service_id' => $this->credentials['service_id'],
				];
				break;

			case 'smsoffice':
				$provider_params = [
					'key' => $this->credentials['key'],
					'sender' => $this->credentials['brand'],
				];
				break;

			case 'smsco':
				$provider_params = [
					'username' => $this->credentials['username'],
					'password' => $this->credentials['password'],
				];
				break;
		}

		return array_merge($provider_params, $additional_params);
	}

	/**
	 * cURL request \w additional params
	 *
	 * @param string $method
	 * @param string $purpose
	 * @param array $additional_params
	 *
	 * @return array
	 */
	private function cURL($purpose = 'send', $additional_params = [], $method = 'get')
	{
		$ch = curl_init();

		if ($method == 'post') {
			curl_setopt($ch, CURLOPT_URL, $this->GenerateURL($purpose));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->GetParams($additional_params));
		} else {
			curl_setopt($ch, CURLOPT_URL, $this->GenerateURL($purpose, $this->BuildQuery($additional_params)));
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		$info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return [
			'info' => $info,
			'result' => $result,
		];
	}

	/**
	 * Send message with receivers
	 *
	 * @param $numbers
	 * @param $message
	 * @param array $additional_params
	 *
	 * @return array
	 * @throws \Gabievi\SMS\SMSException
	 */
	public function Send($numbers, $message, $additional_params = [])
	{
		$to = is_array($numbers) ? implode(',', $numbers) : $numbers;
		
		// send sms request to provider
		switch ($this->provider) {
			case 'magti':
				$response = $this->cURL('send', array_merge([
					'to' => $to,
					'message' => urlencode($message),
				], $additional_params));

				if ($response['info'] >= 200 && $response['info'] < 300) {
					$result = explode('-', $response['result']);

					return [
						'code' => $result[0],
						'msg_id' => (int)$result[1],
					];
				}
				break;

			case 'smsoffice':
				$reference = uniqid();

				$response = $this->cURL('send', array_merge([
					'destination' => $to,
					'content' => $message,
					'reference' => $reference,
				], $additional_params));

				if ($response['info'] >= 200 && $response['info'] < 300) {
					return [
						'code' => (int)$response['result'],
						'reference' => $reference,
					];
				}
				break;

			case 'smsco':
				$response = $this->cURL('send', array_merge([
					'recipient' => $to,
					'message' => urlencode($message),
					'balance' => true,
				], $additional_params));

				if ($response['info'] >= 200 && $response['info'] < 300) {
					$result = explode(' ', $response['result']);

					return [
						'code' => $result[0] == 'OK' ? 0 : $result,
						'msg_id' => $result[0] == 'OK' ? $result[2] : null,
					];
				}
				break;
		}

		throw new SMSException(__FUNCTION__);
	}

	/**
	 * Send scheduled messages
	 *
	 * @param $numbers
	 * @param $message
	 * @param $datetime
	 *
	 * @return mixed
	 * @throws \Gabievi\SMS\SMSException
	 */
	public function Schedule($numbers, $message, $datetime)
	{
		switch ($this->provider) {
			case 'smsco':
				return $this->Send($numbers, $message, [
					'schedule' => date('Y-m-d H:i:s', strtotime($datetime)),
				]);
				break;
		}

		throw new SMSException(__FUNCTION__);
	}

	/**
	 * Get message status using message id
	 *
	 * @param $msg_id
	 *
	 * @return array
	 * @throws \Gabievi\SMS\SMSException
	 */
	public function Status($msg_id)
	{
		switch ($this->provider) {
			case 'magti':

				return $this->cURL('status', [
					'message_id' => $msg_id
				]);
				break;

			case 'smsco':
				return $this->cURL('status', [
					'mes_id' => $msg_id
				]);
				break;
		}

		throw new SMSException(__FUNCTION__);
	}

	/**
	 * Get balance from provider
	 */
	public function Balance()
	{
		switch ($this->provider) {
			case 'smsoffice':
				return file_get_contents($this->GenerateURL('balance'));
				break;
		}

		throw new SMSException(__FUNCTION__);
	}
}
