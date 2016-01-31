#SMS



[![Latest Stable Version](https://poser.pugx.org/zgabievi/sms/version.png)](https://packagist.org/packages/zgabievi/sms)
[![Total Downloads](https://poser.pugx.org/zgabievi/sms/d/total.png)](https://packagist.org/packages/zgabievi/sms)
[![License](https://poser.pugx.org/zgabievi/sms/license)](https://packagist.org/packages/zgabievi/sms)

SMS System for [Laravel 5.*](http://laravel.com/)

## Table of Contents
- [Installation](#installation)
    - [Composer](#composer)
    - [Laravel](#laravel)
- [Usage](#usage)
    - [MSG](#msg)
    - [SMSOffice](#smsoffice)
- [Config](#config)
- [License](#license)

## Installation

### Composer

Run composer command in your terminal.

    composer require zgabievi/sms

### Laravel

Open `config/app.php` and find the `providers` key. Add `SMSServiceProvider` to the array.

```php
Gabievi\SMS\SMSServiceProvider::class
```

Find the `aliases` key and add `Facade` to the array. 

```php
'SMS' => Gabievi\SMS\Facades\SMS::class
```

## Usage

### MSG

You can send messages using method:

```php
SMS::send($receiver, $message)
```

Where `$receiver` will be phone number without **+** or **00** symbols

and `$message` will be text to be sent

Return after success will be:

```json
{
    "success": true,
    "code": STATUS_CODE,
    "message_id": MESSAGE_ID
}
```

and after error:

```json
{
    "success": false
}
```

---

You can check status using method:

```php
SMS::check($message_id)
```

Where `$message_id` will be MESSAGE_ID from send method.

Return after success will be:

```json
{
    "success": true,
    "code": STATUS_CODE
}
```

and after error:

```json
{
    "success": false
}
```

### SMSOffice

You can send messages using method:

```php
SMS::send($receiver, $message)
```

Where `$receiver` will be phone number without **+** or **00** symbols

several numbers implode with **,**. Ex: 995592000000,995593111111,995594222222

and `$message` will be text to be sent. Max: 480 symbols

Return after success will be:

```json
{
    "success": true,
    "code": STATUS_CODE,
    "reference": UNIQID
}
```

and after error:

```json
{
    "success": false
}
```

---

To see your balance use:

```php
SMS::getBalance()
```

You will get number of messages you have left

## Config

Publish SMS config file using command:

```
php artisan vendor:publish
```

Created file `config\sms.php`. Inside you can change configuration as you wish.

Set default provider `smsoffice` or `msg`

```php
'default' => 'smsoffice'
```

You can configure provider credentials in your config or `.env` file

Environment keys are:

#### MSG

```
MSG_USERNAME
MSG_PASSWORD
MSG_CLIENT_ID
MSG_SERVICE_ID
```

#### SMSOffice

```
SMS_KEY
SMS_SENDER
```

## License

SMS is an open-sourced laravel package licensed under the [MIT license](http://opensource.org/licenses/MIT).

## TODO
- [ ] Need to be good tested
- [ ] Add more providers
- [ ] Write test cases
