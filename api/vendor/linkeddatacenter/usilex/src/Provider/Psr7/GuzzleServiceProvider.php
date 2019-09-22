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
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use Zend\HttpHandlerRunner\Emitter\SapiStreamEmitter;
use Exception;

/**
 * This service provider uses Guzzle\PSr7 to resolve uSilex as PSR-7 dependencies.
 *
 * As default, uSilex.responseEmitter uses SapiStreamEmitter class.
 *
 * As default, uSilex.exceptionHandler uses JsonResponse as custom response with 500 as error code.
 *
 *
 * Add this dependencies to your project:
 *
 * composer require guzzlehttp/Psr7
 * composer require zendframework/zend-httphandlerrunner
 *
 * USAGE:
 *     $app->register( new \uSilex\Provider\Psr7\GuzzleProvider() );
 *
 */

class GuzzleServiceProvider implements ServiceProviderInterface
{

    
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['uSilex.request'] = function () {
            return ServerRequest::fromGlobals();
        };
        
        $app['uSilex.responseEmitter'] = $app->protect(function ($response) {
            (new SapiStreamEmitter())->emit($response);
        });
              
        $app['uSilex.exceptionHandler'] = $app->protect(function ($e) {
            return new Response(500, [], $e->getMessage());
        });
    }
}
