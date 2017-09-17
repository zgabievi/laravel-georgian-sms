<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS Provider
    |--------------------------------------------------------------------------
    |
    | You can specify any allowed sms service provider from list below:
    | Allowed providers are: 'magti', 'smsoffice', 'smsco'
    |
    */

    'default' => env('SMS_GATEWAY', 'magti'),

    /*
    |--------------------------------------------------------------------------
    | SMS Provider Credentials
    |--------------------------------------------------------------------------
    |
    | Here you must specify credentials required from provider
    | This credentials will be used in protocol
    |
    */

    'providers' => [

        'smsoffice' => [
            'key' => env('SMS_PASSWORD', 'SECRET_KEY'),
            'brand' => env('SMS_USERNAME', 'BRAND_NAME'),
        ],

        'smsco' => [
            'username' => env('SMS_USERNAME', 'USERNAME'),
            'password' => env('SMS_PASSWORD', 'PASSWORD'),
        ],

        'magti' => [
            'username' => env('SMS_USERNAME', 'USERNAME'),
            'password' => env('SMS_PASSWORD', 'PASSWORD'),
            'client_id' => env('SMS_CLIENT_ID', 'CLIENT_ID'),
            'service_id' => env('SMS_SERVICE_ID', 'SERVICE_ID'),
        ],

    ],

];
