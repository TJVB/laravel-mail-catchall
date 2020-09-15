# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]

## 3.0.0
### Changed
- Be clear about the PHP 7.2, 7.3 and 7.4 support.
- Use the Psr\Log\LoggerInterface and not the Log facade.
- Use the ViewFactory and Config repository instead of the helper functions.
- BC break: the Mail Catcher construct signature is changed. 

## Added
- Support Laravel 8
### Removed
- Support Laravel 5.7 - 5.8

## 2.3.0
## Added
- Support Laravel 7
- Tests for PHP 7.4
 
## 2.2.0
## Added
- Support Laravel 6.0

## 2.1.0
### Added
- Support PHP 7.3

## 2.0.1
### Changed
- Fix PHPUnit version constrains in composer.json
- Fix compatibility with PHPUnit 7 & 8

## 2.0.0
### Added
- Support Laravel 5.8 (see [#4](https://gitlab.com/tjvb/laravel-mail-catchall/issues/4) )
### Removed
- Support Laravel 5.0 - 5.6
- Support PHP 7.0 and 7.1

## 1.1.0
- exclude test data from the zip for composer
- move phpunit.xml to phpunit.xml.dist and ignore phpunit.xml to let other people overwrite it with there own configuration
- Remove phpmd from te require dev part of composer.json and use the phar file to test it
- Support Laravel 5.7 (see [#1](https://gitlab.com/tjvb/laravel-mail-catchall/issues/1) )
- Remove the composer.lock file (see [#2](https://gitlab.com/tjvb/laravel-mail-catchall/issues/2) )
- Change the version contrainces in composer.json to a version with min and max. Allow the different working orchestra/testbench versions

## 1.0.1
- Add tests for the different Laravel versions 

## 1.0.0
- Update the readme with the custom config
- Update the views for displaying the receivers if the array key is empty or there isn't a value

## 0.2.0
- Add the option to add the original to, cc and bcc to the email body
- Publish the views

## 0.1.0
- Add an event handling to the Laravel sended mail
- Set the receiver with an env variable
