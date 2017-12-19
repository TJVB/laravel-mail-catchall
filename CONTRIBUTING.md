# Contributing
Contributions are allways very welcome. This file is a guideline about how to contribute. 

## How to contribute
All the contributes need to be done with a merge request. It is possible to create a merge request prefixed with WIP: to ask for feedback or if you didn't know how to match all requirements.  
Please be sure to check all the [requirements](#requirements) before sending your merge request (except a WIP merge request)

## Requirements
* All the code need to confirm to the [PSR-2](http://www.php-fig.org/psr/psr-2/). You can check this localy with `vendor/bin/phpcs --standard=psr2 src/`
* Add tests for code changes, we use [PHPUnit](https://phpunit.de/). You can run the test with `vendor/bin/phpunit` this wil also generate some reports in the build directory.
* Document the changes, any functional change or bug fix need to be writen in [CHANGELOG.md](CHANGELOG.md). Depending on your change you need to add some documentation to the [README.md](README.md)
* Respect [SemVer](http://semver.org/), we use Semanting Versioning so please respect it with the changes you want to add.
* A merge request for a change. Please don't mix multiple changes in one merge request.
* Ask questions, if you are not sure about something ask it. 

