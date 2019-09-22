# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.1.0] - 2018-08-04

### Added

- PSR-17 support
- New option `responseFactory`

## [1.0.0] - 2018-01-25

### Added

- Improved testing and added code coverage reporting
- Added tests for PHP 7.2

### Changed

- Upgraded to the final version of PSR-15 `psr/http-server-middleware`

### Fixed

- Updated license year

## [0.5.0] - 2017-11-13

### Changed

- Replaced `http-interop/http-middleware` with  `http-interop/http-server-middleware`.

### Removed

- Removed support for PHP 5.x.

## [0.4.0] - 2017-09-21

### Changed

- Append `.dist` suffix to phpcs.xml and phpunit.xml files
- Changed the configuration of phpcs and php_cs
- Upgraded phpunit to the latest version and improved its config file
- Updated to `http-interop/http-middleware#0.5`

## [0.3.1] - 2017-03-30

### Fixed

- `Middlewares\Cache` is executed only with `GET` and `HEAD` requests.

## [0.3.0] - 2016-12-26

### Changed

- Updated tests
- Updated to `http-interop/http-middleware#0.4`
- Updated `friendsofphp/php-cs-fixer#2.0`

## [0.2.0] - 2016-11-22

### Changed

- Updated to `http-interop/http-middleware#0.3`

## 0.1.0 - 2016-10-03

First version


[1.1.0]: https://github.com/middlewares/cache/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/middlewares/cache/compare/v0.5.0...v1.0.0
[0.5.0]: https://github.com/middlewares/cache/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/middlewares/cache/compare/v0.3.1...v0.4.0
[0.3.1]: https://github.com/middlewares/cache/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/middlewares/cache/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/middlewares/cache/compare/v0.1.0...v0.2.0
