# Laravel Mail Catchall
[![pipeline status](https://gitlab.com/tjvb/laravel-mail-catchall/badges/master/pipeline.svg)](https://gitlab.com/tjvb/laravel-mail-catchall/commits/master)
[![coverage report](https://gitlab.com/tjvb/laravel-mail-catchall/badges/master/coverage.svg)](https://gitlab.com/tjvb/laravel-mail-catchall/commits/master)

## Why
The target is to catch all the mail send by Laravel and send it to a configured email adres. We found it usefull to use it for acceptance environments where the client can check all the mail that is send by the application. This also catch the email that is send by a queue runner or an artisan command.

## Alternative
If you just want to see the mail directly and don't use a queue or artisan task to send the mail. You can use the Laravel Mail Preview Driver from Mohamed Said [https://github.com/themsaid/laravel-mail-preview](https://github.com/themsaid/laravel-mail-preview) 

## Installation
You can install the Laravel Mail Catcher with composer with the command: `composer require tjvb/laravel-mail-catchall`

### Before Laravel 5.5
Add `TJVB\MailCatchall\MailCatchallServiceProvider::class,` to the providers array in config/app.php

### Publish the config file
Publish the config file with `php artisan vendor:publish --provider="TJVB\MailCatchall\MailCatchallServiceProvider"`

## Config
Set the `MAILCATCHALL_ENABLED` env variable (most used version is to set it in the .env file) to true  
Set the `MAILCATCHALL_RECEIVER` env variable with the email address if the receiver.

## Optional config
You have more options to change in the mailcatchall.php config file. You can enable or disable the appending of the receivers to the html or text mails.  
The package blade views are published to your views directory (resources/views/vendor/mailcatchall) so that is the place to change the displaying.

## Changelog
We (try to) document all the changes in [CHANGELOG](CHANGELOG.md) so read it for more information.

## Contributing
You are very welcome to contribute, read about it in [CONTRIBUTING](CONTRIBUTING.md)

## Code of Conduct
We have a code of conduct, and suspect everybody who want to involve in this project to respect it. [CODE OF CONDUCT](CODE-OF-CONDUCT.md)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
