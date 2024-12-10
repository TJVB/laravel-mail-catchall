# Contributing

Contributions are always welcome. This file is a guideline about how to contribute. 

## How to contribute

All the contributions need to be done with a merge request. It is possible to create a merge request prefixed with WIP: to ask for feedback or if you didn't know how to match all requirements.  
Please be sure to check all the [requirements](#requirements) before sending your merge request (except a WIP merge request)


## Do Not Violate Copyright

Only submit a PR with your own original code. Do NOT submit a PR containing code which you have largely copied from
an externally projects, unless you wrote said the code yourself.
Open source does not mean that copyright does not apply.
Copyright infringements will not be tolerated and can lead to you being banned from the repository.

## Do Not Submit AI Generated PRs

The same goes for (largely) AI-generated PRs. These are not welcome as they will be based on copyrighted code from others
without accreditation and without taking the license of the original code into account, let alone getting permission
for the use of the code or for re-licensing.

Aside from that, the experience is that AI-generated PRs will be incorrect 100% of the time and cost reviewers too much time.
Submitting a (largely) AI-generated PR will lead to you being banned from the repository.


## Requirements

* All the code need to confirm to the [PSR-12](https://www.php-fig.org/psr/psr-12/). You can check this locally with `vendor/bin/phpcs`
* We use [PHPMD](https://phpmd.org) to validate the quality of the code. You can check it locally with `/path/to/phpmd src text phpmd.xml.dist`
* Add tests for code changes, we use [PHPUnit](https://phpunit.de/). You can run the test with `vendor/bin/phpunit` this will also generate some reports in the build directory.
* Document the changes, any functional change or bug fix need to be written in [CHANGELOG.md](CHANGELOG.md). Depending on your change you need to add some documentation to the [README.md](README.md)
* Respect [SemVer](https://semver.org/), we use Semantic Versioning so please respect it with the changes you want to add.
* A merge request for a change. Please don't mix multiple changes in one merge request.
* Ask questions. This can be if you are not sure about something, or you think about adding something. 
* Only submit the code that you wrote yourself.
