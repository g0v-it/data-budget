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
     * This service provider uses http://relayphp.com/2.x to define
     * uSilex.httpHandler service.
     *
     * Add this dependency to your project:
     *
     * composer require "relay/relay" "2.x@dev"
     *
     * USAGE:
     *     you need do define the service handler.queue that contains the list
     *     of middleware to execute. You can use the id of a service that realize a middleware,
     *     a concrete middleware  instance or a callable with the signatur recognized by relay
     *
     *     $app->register( new RelayServiceProvider() );
     *     $app['handler.queue'] = [
     *         'a_middleware_service_id',      // should be defined $app['a_middleware_service_id']
     *         new MyMiddleware(),             // a class that implements PSR-15 Middleware interface
     *         function( $request, $next ){}   // a closure
     *     ];
     *
     */
namespace uSilex\Provider\Psr15;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Relay\Relay;

class RelayServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $app)
    {
        $app['handler.queue'] = [];
        
        $app['relay.pimpleResolver'] = $app->protect(function ($entry) use ($app) {
            return is_string($entry) ? $app[$entry] : $entry;
        });
        
        $app['relay.factory'] = $app->protect(function ($queue) use ($app) {
            return new Relay($queue, $app['relay.pimpleResolver']);
        });
        
        $app['uSilex.httpHandler'] = function ($app) {
            return $app['relay.factory']($app['handler.queue']);
        };
    }
}
