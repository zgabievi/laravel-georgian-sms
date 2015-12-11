<?php

return [
	/*
	 * Default SMS Provider
	 * Can be: `smsoffice` or `msg`
	 */
	'default' => 'smsoffice',

	'providers' => [
		/*
		 * Credentials for SMSOffice
		 */
		'smsoffice' => [
			'key' => env('SMS_KEY', 'SMSOfficeSecretKey'),
			'sender' => env('SMS_SENDER', 'YourBrandName')
		],

		/*
		 * Credentials for MSG
		 */
		'msg' => [
			'username' => env('MSG_USERNAME', 'MSGUsername'),
			'password' => env('MSG_PASSWORD', 'MSGPassword'),
			'client_id' => env('MSG_CLIENT_ID', 0),
			'service_id' => env('MSG_SERVICE_ID', '0000'),
		],
	]
];