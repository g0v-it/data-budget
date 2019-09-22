![µSilex logo](logo.png)

µSilex
======

[![Latest Version on Packagist](https://img.shields.io/packagist/v/linkeddatacenter/uSilex.svg?style=flat-square)](https://packagist.org/packages/linkeddatacenter/usilex)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/linkeddatacenter/uSilex/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/linkeddatacenter/uSilex/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/linkeddatacenter/uSilex/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/linkeddatacenter/uSilex/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/linkeddatacenter/uSilex/badges/build.png?b=master)](https://scrutinizer-ci.com/g/linkeddatacenter/uSilex/build-status/master)


µSilex (aka micro silex) is a micro framework inspired by Pimple and PSR standards. All with less than 100 lines of code!

This project is a try to build a standard middleware framework for developing micro-services and
APIs endpoints that require maximum performances with a minimum of memory footprint.

Why [Pimple](https://pimple.symfony.com/)? Because it is lazy, consistent, fast, elegant and small (about 80 lines of code). What else? 

Why [PSR standards](https://www.php-fig.org/psr)? Because it is a successful community project with a lot of good implementations (psr15-middlewares, Zend stratigility, Guzzle, etc. etc.).

Why µSilex? Silex was a great framework now abandoned in favor of Symfony + Flex. This is good when you need more power and flexibility. But you have to pay a price in terms of complexity and memory footprint. 
µSilex it is a new project that covers a small subset of the original Silex project: a µSilex Application is just a Pimple Container implementing the [PSR-15 specifications](https://www.php-fig.org/psr/psr-15/). That's it. 

As a matter of fact, in the JAMStack, Docker and XaaS era, you can let a lot of conventional framework features to other components in the system application architecture (i.e. caching, authentication, security, monitoring, rendering, etc. etc).

Is µSilex a replacement of Silex? No, but it could be used to build your own "Silex like"framework.

There are alternatives to µSilex? Yes of course. For example, the [Zend  Expressive](https://docs.zendframework.com/zend-expressive/) component of the Zend Framework shares similar principles. But it is not "container focused" and it is bound to Zend libraries. Besides routing, Zend Expressive implements "piping" as a mechanism for adding middlewares to your application.

µSilex is based on a few principles:

- keep it **simple**: so you can understand all your code;
- keep it **small**: so you can control your project;
- keep it **fast**: well, keep it faster...;
- use **PSR standards**: do not reinvent the wheel;
- adopt the **middlewares** architecture;
- **"one-for-all" does not exist!**. And µSilex is not an exception. Select the right framework for your problem.

Have a nice day!


## Install

`compose require linkeddatacenter/usilex`

## Overview

Basically a µSilex provides the class **Application** that is a Pimple container that implements both the PSR-15 middleware interface and PSR-11 Container interface.

Middleware is now a very popular topic in the developer community, The idea behind it is “wrapping” your application logic with additional request processing logic, and then chaining as much of those wrappers as you like. So when your server receives a request, it would be first processed by your middlewares, and then after you generate a response it will also be processed by the same set (image from Zend Expressive).

![architecture](architecture.png)

Note that in this model, the traditional *routing by controller* is just an optional step in the middleware pipeline.

A middleware is a piece of software that implements the PSR-15 middleware interface:

```php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class MyMiddleware implements MiddlewareInterface 
{
    public function process(
        ServerRequestInterface $request, 
        RequestHandlerInterface $handler
        ): ResponseInterface
    {
        //here your code that returns a response or passes the control to the handler
    }
}  
```


µSilex is not bound to any specific implementations (apart from Pimple) nor 
to any middleware implementation.

Instead, µSilex realizes a framework to use existing standard implementations. µSilex adopts PSR-7 specifications for HTTP messages, PSR-15 for managing HTTP handles and middleware and PSR-11 for containers.

## Usage

To bind µSilex with specific interface specifications, you need to configure some entries in the container:

- **uSilex.request**: a service that instantiates an implementation of PSR-7 server request object 
- **uSilex.responseEmitter**: an optional callable that echoes the HTTP. If not provided, no output is generated. 
- **uSilex.exceptionHandler** a callable that generates an HTTP response from a PHP Exception. If not provided just an HTTP 500 header with a text body is output
- **uSilex.httpHandler**: a service that instantiates an implementation of the PSR-15 HTTP handler

µSilex Application exposes the *run* method that realizes typical server process workflow:
- creates a request using uSilex.request service
- calls the uSilex.httpHandler
- emits the HTTP response calling uSilex.responseEmitter

If some PHP exceptions are thrown in the process, they are translated in Response by uSilex.exceptionHandler and then emitted by uSilex.responseEmitter.

The signature for uSilex.responseEmitter is `function ($response) { echo ....}` . 
The signature for uSilex.exceptionHandler is `function ($exception, $request) {}`.

There are tons of libraries that implement great reusable middlewares and HTTP handlers that are fully compatible with µSilex. For example see [MW library](https://github.com/middlewares/psr15-middlewares)). µSilex is also compatible with a lot of Silex Service Providers and with some Silex Application traits.

You can create your custom framework just selecting the components that fit your needs. 
This fragment  uses the [Relay](http://relayphp.com/2.x) library for PSR-15 http handler  and [Diactoros](https://docs.zendframework.com/zend-diactoros/) for PSR-7 http messages.

```php
require_once __DIR__.'/../vendor/autoload.php';
include "MyMiddleware.php"; // here your MyMiddleware class definition
$app = new \uSilex\Application;
$app['uSilex.request'] = \Zend\Diactoros\ServerRequestFactory::fromGlobals();
$app['uSilex.responseEmitter'] = $app->protect(function($response) {echo $response->getBody(); });
$app['uSilex.httpHandler'] = function($app) { 
    return new \Relay\Relay([new MyMiddleware($app)]); 
};
$app->run();
```

### the µSilex service providers

out-of-the-box µSilex give to you a set of Service Providers that you can use as a model 
to implement yours.


#### Provider\Psr7\DiactorosServiceProvider

Bound a µSilex application to the [Zend Diactoros](https://docs.zendframework.com/zend-diactoros/) implementation for Psr7 specifications.

#### Provider\Psr7\GuzzleServiceProvider

Bound a µSilex application to the [Guzzle](http://docs.guzzlephp.org/en/stable/psr7.html) implementation for Psr7 specifications.


#### Provider\Psr15\RelayServiceProvider

Bound a µSilex application to [Relay](https://github.com/relayphp/Relay.Relay), a fast, no frill implementation of the PSR-15 specifications.

#### Provider\Psr15\ZendPipeServiceProvider

Bound a µSilex application to *MiddlewarePipe* part of the [zend-stratigility library](https://github.com/zendframework/zend-stratigility/) Psr15 implementation.

### Configuring new service providers

µSilex Service provider are normal Pimple service providers that, optionally, define the method *boot*. This method will be called only once by the application method *boot*. Use this feature only if strictly necessary. The boot method is called automatically by the Application run method.


A best practice to write a PSR-15 service provider is to allow users to declare middlewares as Pimple services and to allow users to define the middleware queue (i.e. pipeline) in an array with the name *handler.queue*. The *handler.queue* element can also be a service that resolves in an implementation of the iterable interface. For instance:

```php
...
$app= new Application;
$app->register( new MyPsr7ServiceProvider() };
$app['my.router'] = function($app) { return new \My\RouterMiddleWare($app) };
$app['my.notfound'] = function($app) { return new \My\NotFoundMiddleWare($app) };
$app['handler.queue'] = [
    'my.router'
    'my.notfound'
];
$app['uSilex.httpHandler'] = function($app) {
   return new MyHttpHandler($app['handler.queue']);
};
$app->boot()->run();
```

### Other tools

µSilex also provides two ready to use anti-pattern traits: \uSilex\Psr11Trait that implements a PSR-11 interface and \uSilex\ContainerAwareTrait that attach a PSR-11 container (e.g a µSilex Application) to any object. 


## A complete example

```php
<?php
require_once __DIR__.'/../vendor/autoload.php';
use uSilex\Application;
use uSilex\ContainerAwareTrait;
use uSilex\Provider\Psr15\RelayServiceProvider as Psr15Provider;
use uSilex\Provider\Psr7\DiactorosServiceProvider as Psr7Provider ;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Diactoros\Response\TextResponse;

class MyMiddleware implements MiddlewareInterface {
    use ContainerAwareTrait;
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        return new \Zend\Diactoros\Response\TextResponse( $this->containerGet('message', 'Hi'));
    }
}  

$app = new Application;
$app->register(new Psr15Provider());
$app->register(new Psr7Provider());
$app['myMiddleware'] = function($app) { return new MyMiddleware($app); };
$app['message'] = 'hello world!';
$app['handler.queue'] = ['myMiddleware'];
$app->run();
```


See more examples in the HTML directory.

## Developers quick start

Install [docker](https://www.docker.com/) and run

```
docker run --rm -ti -p 8000:8000 -v $PWD/.:/app composer bash
composer cs-fix
composer test
composer coverage
composer examples
# Until ctr-c is pressed, point your browser to http://localhost:8000/examples/
exit
```

Please see  [CONTRIBUTING](CONTRIBUTING.md) for contributing details.


## Credits

µSilex is inspired by the following projects:

- https://github.com/php-fig/fig-standards/
- https://github.com/pimple/pimple and https://github.com/silexphp/Silex projects by Fabien Potencier
- https://github.com/relayphp/Relay.Relay project
