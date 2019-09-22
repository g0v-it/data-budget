<?php
namespace examples\routing;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Aura\Router\RouterContainer;
use Middlewares\AuraRouter;
use Middlewares\RequestHandler;
use Middlewares\ErrorHandler;

/**
 * this configuration reuse middleware and other
 * components available from https://github.com/middlewares project
 */
class MiddlewaresServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
            
        // Error handler middleware configuration
        // from: https://github.com/middlewares/error-handler
        $app['errorHandlingMiddleware'] = function () {
            return (new ErrorHandler())->catchExceptions(true);
        };
        
        
        // aura routing middleware configuration
        // from: https://github.com/middlewares/aura-router
        $app['basepath'] = '/';
        $app['auraRouterMiddleware'] = function ($app) {
            $routeContainer = new RouterContainer($app['basepath']);
            $routeMap = $routeContainer->getMap();
           
            include "routes.php";

            return new AuraRouter($routeContainer);
        };
        
        
        // register the RequestHandler
        // from https://github.com/middlewares/request-handler
        $app['requestHandlerMiddleware'] = function () {
            return new RequestHandler();
        };
    }
}
