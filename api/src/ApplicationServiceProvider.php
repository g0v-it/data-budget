<?php

/*
 * This file is part of the LODMAP2D PHP SDK project.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LODMAP2D;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use uSilex\Api\BootableProviderInterface;

class ApplicationServiceProvider implements ServiceProviderInterface
{
    public function ciao($request)
    {
        $name = $request->getAttribute('name');
        return sprintf('Hello %s', $name);
    }
    
    
    public function register(Container $app)
    {
        $app['backend'] = 'http://sdaas:8080/sdaas/sparql'; // Override with LODMAP2D_BACKEND env variable
        
        /*
         * Define PSR Implementations and Middleware Pipeline
         */
        $app->register(new \uSilex\Provider\Psr7\GuzzleServiceProvider());
        $app->register(new \uSilex\Provider\Psr15\RelayServiceProvider());
        $app['handler.queue'] = [
            'urlRewriter',          // rewrite request path
            'cors',                 //Cross-Origin Resource Sharing (CORS)
            'gzipEncoder',          //Compress the response to gzip
            'cache',                //Add cache expiration headers
            'fastRoute',            //Handle the routes with fast-route
            'requestHandler',       //Handle the route
        ];
        
        
        
        // Select httpClient implementation
        $app['store'] = function ($app) {
            return new \GuzzleHttp\Client(
                ['base_uri' => $app['backend'] ]
            );
        };
        
        
        $app['controller'] = function ($app) {
            return new Controller($app);
        };
        
        
        /****************************************
         *             MIDDLEWARES
         ****************************************/
        
        // remove extension form url
        $app['urlRewriter'] = function ($app) {
            return new \LinkedDataCenter\UrlRewriter([ '/(.*)\\.ttl' => '/$1' ]);
        };
        
        //Cross-Origin Resource Sharing (CORS)
        $app['cors'] = function () {
            $settings = (new \Neomerx\Cors\Strategies\Settings())
                ->setRequestAllowedOrigins(['*'])
            ;
            $analyzer = \Neomerx\Cors\Analyzer::instance($settings);
            return new \Middlewares\Cors($analyzer);
        };
        
        
        //Compress the response to gzip
        $app['gzipEncoder'] = function () {
            return new \Middlewares\GzipEncoder();
        };
        
        
        //Add cache expiration headers
        $app['cache'] = function () {
            return (new \Middlewares\Expires())
                ->defaultExpires('+1 hour') // 1 hour to everything else
            ;
        };


        // Routes http requests
        $app['fastRoute'] = function () {
            //Create the router dispatcher
            $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
                $r->addRoute('GET', '/{resource:app|credits|terms|accounts}', 'controller');
                $r->addRoute('GET', '/{resource:partition|account}/{id}', 'controller');
            });
            
            return new \Middlewares\FastRoute($dispatcher);
        };
        
        
        // execute the matching route
        $app['requestHandler'] = function ($app) {
            return new \Middlewares\RequestHandler($app);
        };
    }
}
