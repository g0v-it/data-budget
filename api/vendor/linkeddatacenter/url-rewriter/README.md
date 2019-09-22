# linkeddatacenter/url-rewriter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status](https://scrutinizer-ci.com/g/linkeddatacenter/url-rewriter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/linkeddatacenter/url-rewriter/build-status/master)
[![Quality Score][ico-scrutinizer]][link-scrutinizer]

Simple Middleware to rewrite the path, the query and the fragment of the an http request uri.
It requires an array of rules that are evaluated in sequence.
A rule is a two position array: array[0] is the regexp pattern to search (internally translated in #^$pattern$# and
array[1] is the replacement according the php function *preg_replace*.

Inspired from [middlewares/base-path](https://github.com/middlewares/base-path)

## Requirements

* PHP >= 7.0
* A [PSR-7](https://packagist.org/providers/psr/http-message-implementation) http message implementation ([Diactoros](https://github.com/zendframework/zend-diactoros), [Guzzle](https://github.com/guzzle/psr7), [Slim](https://github.com/slimphp/Slim), etc...)
* A [PSR-15 middleware dispatcher](https://github.com/middlewares/awesome-psr15-middlewares#dispatcher)

## Installation

This package is installable and autoloadable via Composer as [linkeddatacenter/url-rewriter](https://packagist.org/packages/linkeddatacenter/url-rewriter).

```sh
composer require linkeddatacenter/url-rewriter
```

## Example

```php
$dispatcher = new Dispatcher([
	new Middlewares\BasePath([
	[
            '/(\w+)' => '/$1/pluto',
            '/(\w+)/(\w+)/(\w+).(csv|json|xml)(.*)' =>'/$1/docstore?db=$2&table=$3&format=$4$5',
	])
]);

$response = $dispatcher->dispatch(new ServerRequest());
```

## Options

#### `__construct(array $rules)`

rules := array [ <rule>, <rule>,.... ]
rule := array [ <pattern>, <replace> ]

## Test with docker

```bash
docker run --rm -ti -v $PWD/.:/app composer bash
composer install
vendor/bin/phpunit
```

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/linkeddatacenter/url-rewriter.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/linkeddatacenter/url-rewriter/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/linkeddatacenter/url-rewriter.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/linkeddatacenter/url-rewriter.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/linkeddatacenter/url-rewriter
[link-travis]: https://travis-ci.org/linkeddatacenter/url-rewriter
[link-scrutinizer]: https://scrutinizer-ci.com/g/linkeddatacenter/url-rewriter
