# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [Unreleased]

### Added
- Support PHP 8.4

### Changed
- Change the information in the about command to be more explicit if it is enabled or not.


## 4.4.0 - 2024-03-22

### Added
- Add Laravel 11 support.


## 4.3.0 - 2023-11-26

### Added
- Support PHP 8.3


## 4.2.0 - 2023-02-14

### Added
- Add the enabled status and receiver to the Laravel about command if possible.
- Support for Laravel 10.


## 4.1.0 - 2022-12-20

## Added
- Support PHP 8.2


## 4.0.0 - 2022-02-08
### Added
- Add support for Laravel 9

### Removed
- Removed support for Laravel 7 and 8
- Removed support for PHP 7.3 and 7.4

## [3.2.0] - 2021-12-01
### Added
- Support PHP 8.1

## 3.1.0
### Added
- Support PHP 8.0

## 3.0.0
### Changed
- Be clear about the PHP 7.2, 7.3 and 7.4 support.
- Use the Psr\Log\LoggerInterface and not the Log facade.
- Use the ViewFactory and Config repository instead of the helper functions.
- BC break: the Mail Catcher construct signature is changed. 

### Added
- Support Laravel 8
### Removed
- Support Laravel 5.7 - 5.8

## 2.3.0
### Added
- Support Laravel 7
- Tests for PHP 7.4
 
## 2.2.0
### Added
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
