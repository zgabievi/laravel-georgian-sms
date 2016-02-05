# SMS

[![SMS](http://i.imgsafe.org/30339a3.png)](https://github.com/zgabievi/SMS)

[![Latest Stable Version](https://poser.pugx.org/zgabievi/sms/version.png)](https://packagist.org/packages/zgabievi/sms)
[![Total Downloads](https://poser.pugx.org/zgabievi/sms/d/total.png)](https://packagist.org/packages/zgabievi/sms)
[![License](https://poser.pugx.org/zgabievi/sms/license)](https://github.com/zgabievi/SMS)

Georgian SMS Providers Integration for [Laravel 5.*](http://laravel.com/)

## Table of Contents
- [Installation](#installation)
    - [Composer](#composer)
    - [Laravel](#laravel)
- [Methods](#methods)
- [Config](#config)
- [License](#license)

## Installation

### Composer

Run composer command in your terminal.

    composer require zgabievi/sms

### Laravel

Open `config/app.php`, find the `providers` and add `SMSServiceProvider` to the array.

```php
'providers' => [
    // ...
    Gabievi\SMS\SMSServiceProvider::class,
],
```

Find the `aliases` and add `Facade` to the array. 

```php
'aliases' => [
    // ...
    'SMS' => Gabievi\SMS\SMSFacade::class,
],
```

## Methods

| Method                                                 | MAGTI | SMSOFFICE | SMSCO |
|--------------------------------------------------------|-------|-----------|-------|
| SMS::Send($numbers, $message, $additional_params = []) |   +   |     +     |   +   |
| SMS::Schedule($numbers, $message, $datetime)           |   -   |     -     |   +   |
| SMS::Status($msg_id)                                   |   +   |     -     |   +   |
| SMS::Balance()                                         |   -   |     +     |   -   |

- `$numbers` - comma separated numbers or number, with format: **9955XXXXXXXX**
- `$message` - Text message wich will be sent to the numbers.
- `$additional_params` - Array of key => values that will be used as http query. (Use this only if you know what you are doing)
- `$datetime` - Datetime in format `Y-m-d H:i:s`.
- `$msg_id` - Message ID, which you will get from provider, to check status in future.

Allowed symbols to use in message:

| Symbol | Description                                                      |
|--------|------------------------------------------------------------------|
| a-z    | Characters in the range between **a** and **z** (case sensitive) |
| A-Z    | Characters in the range between **A** and **Z** (case sensitive) |
| 0-9    | Character in the range between **0** and **9**                   |
| .      | Point                                                            |
| _      | Undercsore                                                       |
| -      | Dash                                                             |
| "      | Double Quotes                                                    |
| '      | Single Quote                                                     |
|        | Space                                                            |

## Config

Publish SMS config file using command:

    php artisan vendor:publish

This will create file `config\sms.php`:

### Default SMS Provider

You can specify any allowed sms service provider from list below:

Allowed providers are: 'magti', 'smsoffice', 'smsco'

```php
'default' => 'magti',
```

### SMS Provider Credentials

Here you must specify credentials required from provider

This credentials will be used in protocol

```php
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
```

## .ENV
You can configure provider credentials in your config or `.env` file

| KEY            | MAGTI | SMSOFFICE | SMSCO |
|----------------|-------|-----------|-------|
| SMS_USERNAME   |   +   |   BRAND   |   +   |
| SMS_PASSWORD   |   +   |    KEY    |   +   |
| SMS_CLIENT_ID  |   +   |     -     |   -   |
| SMS_SERVICE_ID |   +   |     -     |   -  |

## License

SMS is an open-sourced laravel package licensed under the [MIT license](http://opensource.org/licenses/MIT).
