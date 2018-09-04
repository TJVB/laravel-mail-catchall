# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]
- exclude test data from the zip for composer
- move phpunit.xml to phpunit.xml.dist and ignore phpunit.xml to let other people overwrite it with there own configuration
- Remove phpmd from te require dev part of composer.json and use the phar file to test it
- Support Laravel 5.7 (see [#1](https://gitlab.com/tjvb/laravel-mail-catchall/issues/1) )
- Remove the composer.lock file (see [#2](https://gitlab.com/tjvb/laravel-mail-catchall/issues/2) )

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
