# Laravel Mail Catchall

[![Latest Stable Version](https://poser.pugx.org/tjvb/laravel-mail-catchall/v)](https://packagist.org/packages/tjvb/laravel-mail-catchall)
[![Pipeline status](https://gitlab.com/tjvb/laravel-mail-catchall/badges/master/pipeline.svg)](https://gitlab.com/tjvb/laravel-mail-catchall/-/pipelines?page=1&scope=all&ref=master)
[![Coverage report](https://gitlab.com/tjvb/laravel-mail-catchall/badges/master/coverage.svg)](https://gitlab.com/tjvb/laravel-mail-catchall/-/pipelines?page=1&scope=all&ref=master)
[![Tested on PHP 8.0 to 8.3](https://img.shields.io/badge/Tested%20on-PHP%208.0%20|%208.1%20|%208.2%20|%208.3-brightgreen.svg?maxAge=2419200)](https://gitlab.com/tjvb/laravel-mail-catchall/-/pipelines?page=1&scope=all&ref=master)
[![Tested on Laravel 9 to 10](https://img.shields.io/badge/Tested%20on-Laravel%209%20|%2010-brightgreen.svg?maxAge=2419200)](https://gitlab.com/tjvb/laravel-mail-catchall/-/pipelines?page=1&scope=all&ref=master)
[![Latest Unstable Version](https://poser.pugx.org/tjvb/laravel-mail-catchall/v/unstable)](https://packagist.org/packages/tjvb/laravel-mail-catchall)


[![PHP Version Require](https://poser.pugx.org/tjvb/laravel-mail-catchall/require/php)](https://packagist.org/packages/tjvb/laravel-mail-catchall)
[![Laravel Version Require](https://poser.pugx.org/tjvb/laravel-mail-catchall/require/laravel/framework)](https://packagist.org/packages/tjvb/laravel-mail-catchall)
[![PHPMD](https://img.shields.io/badge/PHPMD-checked-brightgreen.svg)](https://gitlab.com/tjvb/laravel-mail-catchall/-/blob/master/phpmd.xml.dist)
[![PHPCS](https://img.shields.io/badge/PHPCS-PSR12-brightgreen.svg)](https://gitlab.com/tjvb/laravel-mail-catchall/-/blob/master/phpcs.xml.dist)


[![License](https://poser.pugx.org/tjvb/laravel-mail-catchall/license)](https://packagist.org/packages/tjvb/laravel-mail-catchall)

## Why
The target is to catch all the mail send by Laravel and send it to a configured email address. We found it useful to use it for acceptance environments where the client can check all the mail that is send by the application. This also catch the email that is send by a queue runner or an artisan command.

## Alternative
If you just want to see the mail directly and don't use a queue or artisan task to send the mail. You can use the Laravel Mail Preview Driver from Mohamed Said [https://github.com/themsaid/laravel-mail-preview](https://github.com/themsaid/laravel-mail-preview) 

## Installation
You can install the Laravel Mail Catcher with composer with the command: `composer require tjvb/laravel-mail-catchall`

### Manual register the service provider.
If you disable the package discovery you need to add `TJVB\MailCatchall\MailCatchallServiceProvider::class,` to the providers array in config/app.php

### Publish the config file
Publish the config file with `php artisan vendor:publish --provider="TJVB\MailCatchall\MailCatchallServiceProvider"`

## Config
Set the `MAILCATCHALL_ENABLED` env variable (most used version is to set it in the .env file) to true  
Set the `MAILCATCHALL_RECEIVER` env variable with the email address if the receiver.

## Optional config
You have more options to change in the mailcatchall.php config file. You can enable or disable the appending of the receivers to the HTML or text mails.  
The package blade views are published to your views directory (resources/views/vendor/mailcatchall) so that is the place to change the displaying.

## Changelog
We (try to) document all the changes in [CHANGELOG](CHANGELOG.md) so read it for more information.

## Version compatibility
 Laravel Mail Catchall  | Laravel   | PHP 
:-----------------------|:--------- | :----- 
 1.0                    | 5.0 - 5.6 | 7.0 - 7.2
 1.1                    | 5.0 - 5.7 | 7.0 - 7.2
 2.0                    | 5.7 - 5.8 | 7.2
 2.1                    | 5.7 - 5.8 | 7.2 - 7.3
 2.2                    | 5.7 - 6   | 7.2 - 7.3
 2.3                    | 5.7 - 7   | 7.2 - 7.4
 3.0                    | 6 - 8     | 7.3 - 7.4
 3.1                    | 6 - 8     | 7.3 - 8.0
 3.2                    | 6 - 8     | 7.3 - 8.1
 4.0                    | 9         | 8.0 - 8.1
 
## Contributing
You are welcome to contribute, read about it in [CONTRIBUTING](CONTRIBUTING.md)

## Code of Conduct
We have a code of conduct, and suspect everybody who want to involve in this project to respect it. [CODE OF CONDUCT](CODE-OF-CONDUCT.md)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
