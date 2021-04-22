# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/savannabits/movesms.svg?style=flat-square)](https://packagist.org/packages/savannabits/movesms)
[![Build Status](https://img.shields.io/travis/savannabits/movesms/master.svg?style=flat-square)](https://travis-ci.com/savannabits/movesms)
[![Quality Score](https://img.shields.io/scrutinizer/g/savannabits/movesms.svg?style=flat-square)](https://scrutinizer-ci.com/g/savannabits/movesms)
[![Total Downloads](https://img.shields.io/packagist/dt/savannabits/movesms.svg?style=flat-square)](https://packagist.org/packages/savannabits/movesms)

The PHP SDK for Movetech Solutions' Bulk SMS API (Movesms). See their [Bulk SMS API](https://developers.movesms.co.ke/start.html) for more details.

## Installation

You can install the package via composer:

```bash
composer require savannabits/movesms
```

## Usage

### Required API Params:
* username -	Your account Username
* api_key -	Your API Key
* sender -	Your Sender ID
* to -	Your Recipients separated by commas
* message -	Your Text Message
* msgtype -	Type of the message (use 5 for plain sms)
* dlr -	Type of Delivery Report(use 0 for no delivery Report)

### Send Bulk SMS:

```php
$username = "YOUR MOVETECH USERNAME"; 
$senderId = "YOUR MOVETECH SENDER ID";
$apiKey = "YOUR MOVETECH API KEY";

$recipients = ["+254xxxxxx"]; //Array of recipient phone numbers in international format
$message = "Hello World! Here is my message.";

$res =  Savannabits\Movesms\Movesms::init($username,$apiKey, $senderId)
            ->to($recipients)
            ->message($message)
            ->send();
                        
// Returns a php object with the following format:
$res = [
    "success" => true, //boolean
    "message" => "Message Sent:1701" // Or the error in case success = false
];

```
### Schedule SMS to send Later
```php
$scheduleAt = '2021-04-24 14:04:00'; // Time in the format Y-m-d H:i:s
$res =  Savannabits\Movesms\Movesms::init($username,$apiKey, $senderId)
            ->to($recipients)
            ->message($message)
            ->sendLater($scheduleAt);
```
### Check credit Balance
```php
$res = Savannabits\Movesms\Movesms::checkBalance($apiKey);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email maosa.sam@gmail.com instead of using the issue tracker.

## Credits

- [Sam Maosa](https://github.com/savannabits)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
