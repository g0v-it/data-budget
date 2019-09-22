<?php

/*
 * This file is part of the uSilex framework.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

    /**
     * This service provider uses https://docs.zendframework.com/zend-stratigility to define
     * uSilex.httpHandler service.
     *
     * Add this dependency to your project:
     *
     * composer require zendframework/zend-stratigility
     *
     * USAGE:
     *     you need do define the service handler.queue that contains the list
     *     of middleware to execute. You can use the id of a service that realize a middleware,
     *     a concrete middleware instance or a callable with the signature recognized by relay
     *
     *     $app->register( new ZenPipeServiceProvider() );
     *     $app['handler.queue'] = [
     *      path('/foo', middleware(function ($req, $handler) {
     *         $response = new Response();
     *         $response->getBody()->write('FOO!');
     *
     *         return $response;
     *      })),
     *
     *      new NotFoundHandler(function () {
     *         return new Response();
     *      });
     *     ];
     */
namespace uSilex\Provider\Psr15;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Zend\Stratigility\MiddlewarePipe;

class ZendPipeServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['piper'] = function () {
            return new MiddlewarePipe();
        };
        
        $app['handler.queue'] = [];
        
        $app['uSilex.httpHandler'] = function ($app) {
            $piper = $app['piper'];
            foreach ($app['handler.queue'] as $middlewareService) {
                $piper->pipe($app[$middlewareService]);
            }
            return $piper;
        };
    }
}
