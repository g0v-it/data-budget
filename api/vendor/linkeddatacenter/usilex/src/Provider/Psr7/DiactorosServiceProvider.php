<?php

/*
 * This file is part of the uSilex framework.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace uSilex\Provider\Psr7;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Zend\Diactoros\Response\TextResponse;
use Exception;

/**
 * This service provider uses Zend\Diactoros  to resolve uSilex as PSR-7 dependencies.
 *
 * As default, uSilex.responseEmitter uses SapiStreamEmitter class.
 *
 * As default, uSilex.exceptionHandler uses TextResponse as custom response with 500 as error code.
 *
 *
 * Add this dependencies to your project:
 *
 * composer require zendframework/zend-diactoros
 * composer require zendframework/zend-httphandlerrunner
 *
 * USAGE:
 *     $app->register( new \uSilex\Provider\Psr7\DiactorosProvider() );
 *
 */

class DiactorosServiceProvider implements ServiceProviderInterface
{

    
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['uSilex.request'] = function () {
            return ServerRequestFactory::fromGlobals();
        };
        
        $app['uSilex.responseEmitter'] = $app->protect(function ($response) {
            (new SapiStreamEmitter())->emit($response);
        });
              
        $app['uSilex.exceptionHandler'] = $app->protect(function ($e) {
            return new TextResponse($e->getMessage(), 500);
        });
    }
}
