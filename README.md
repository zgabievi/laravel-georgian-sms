# laravel-georgian-sms

[![Latest Stable Version](https://poser.pugx.org/zgabievi/sms/version?format=flat-square)](https://packagist.org/packages/zgabievi/sms) [![Total Downloads](https://poser.pugx.org/zgabievi/sms/d/total?format=flat-square)](https://packagist.org/packages/zgabievi/sms) [![License](https://poser.pugx.org/zgabievi/sms/license?format=flat-square)](https://packagist.org/packages/zgabievi/sms)

> Georgian SMS providers service integration for [Laravel 5.*](http://laravel.com/) :sunglasses: Trying to make it perfect, easy to use and awesome package :tada: Pull requests are welcome.

## Table of Contents
- [Installation](#installation)
- [Methods](#methods)
- [Config](#config)
- [ENV](#env)
- [License](#license)

## Installation

### Composer

Run composer command in your terminal.

    composer require zgabievi/sms

### Laravel

#### For Laravel 5.5

> If you are using Laravel 5.5, than installation is done. Otherwise follow next steps.

#### For Laravel <= 5.4
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
| SMS::send($numbers, $message, $params = []) | **+** |   **+**   | **+** |
| SMS::schedule($numbers, $message, $datetime)           | **-** |   **-**   | **+** |
| SMS::status($msg_id)                                   | **+** |   **-**   | **+** |
| SMS::balance()                                         | **-** |   **+**   | **-** |

- `$numbers` - comma separated numbers or number, with format: **9955XXXXXXXX**
- `$message` - Text message which will be sent to the numbers.
- `$params` - Array of key => values that will be used as http query. (Use this only if you know what you are doing)
- `$datetime` - Datetime in format `Y-m-d H:i:s`.
- `$msg_id` - Message ID, which you will get from provider, to check status in future.

#### Allowed symbols to use in message:

| Symbol  | Description                                                      |
|---------|------------------------------------------------------------------|
| **a-z** | Characters in the range between **a** and **z** (case sensitive) |
| **A-Z** | Characters in the range between **A** and **Z** (case sensitive) |
| **0-9** | Character in the range between **0** and **9**                   |
| **.**   | Point                                                            |
| **_**   | Undercsore                                                       |
| **-**   | Dash                                                             |
| **"**   | Double Quotes                                                    |
| **'**   | Single Quote                                                     |
|         | Space                                                            |

## Config

Publish SMS config file using command:

    php artisan vendor:publish

This will create file `config\sms.php`:

### Default SMS Provider

You can specify any allowed sms service provider from list below:

Allowed providers are: 'magti', 'smsoffice', 'smsco'

```php
'default' => env('SMS_GATEWAY', 'margti'),
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
| SMS_GATEWAY    | **+** |   **+**   | **+** |
| SMS_USERNAME   | **+** |   BRAND   | **+** |
| SMS_PASSWORD   | **+** |    KEY    | **+** |
| SMS_CLIENT_ID  | **+** |   **-**   | **-** |
| SMS_SERVICE_ID | **+** |   **-**   | **-** |

## License

laravel-georgian-sms is licensed under a  [MIT License](https://github.com/zgabievi/laravel-georgian-sms/blob/master/LICENSE).
