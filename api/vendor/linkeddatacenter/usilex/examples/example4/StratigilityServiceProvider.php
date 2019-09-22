<?php
namespace EXAMPLE;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

use Zend\Diactoros\Response;
use Zend\Stratigility\Middleware\NotFoundHandler;
use function Zend\Stratigility\middleware;
use function Zend\Stratigility\path;

/**
 * this configuration reuse middleware and other
 * components available from Zend Stratigility  component
 */
class StratigilityServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        // using middleware function
        $app['home_page'] = function ($app) {
            return middleware(function ($req, $handler) use ($app) {
                if (!in_array($req->getUri()->getPath(), [$app['basepath'].'/', $app['basepath']], true)) {
                    return $handler->handle($req);
                }
                
                $response = new Response();
                $response->getBody()->write("This is the home. Try '/hi' or  anithing else");
                
                return $response;
            });
        };
        
        // using path function
        $app['hello_page'] = function ($app) {
            return path($app['basepath'].'/hi', middleware(function ($req, $handler) {
                $response = new Response();
                $response->getBody()->write("Hi. Try anything but/hi");
                
                return $response;
            }));
        };
        
        // using standard middleware as defaultt page
        $app['default_page'] = function ($app) {
            return new MyMiddleware($app);
        };
    }
}
