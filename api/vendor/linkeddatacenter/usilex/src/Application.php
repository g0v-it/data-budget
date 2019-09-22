<?php

/*
 * This file is part of the uSilex project.
 *
 * (c) Enrico Fagnoni <enrico@linkeddata.center>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace uSilex;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use uSilex\Api\BootableProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Exception;

/**
 * The uSilex framework class.
*/
class Application extends Container implements MiddlewareInterface, ContainerInterface
{
    protected $providers = [];
    protected $booted = false;

    public function get($id)
    {
        return $this[$id];
    }
    
    public function has($id)
    {
        return isset($this[$id]);
    }
    
    /**
     * Redefine Registers a service provider.
     *
     * @param ServiceProviderInterface $provider A ServiceProviderInterface instance
     * @param array                    $values   An array of values that customizes the provider
     *
     */
    public function register(ServiceProviderInterface $provider, array $values = []) : self
    {
        $this->providers[] = $provider;
        
        parent::register($provider, $values);
        
        return $this;
    }
    
    
    /**
     * Boots all service providers.
     *
     * This method is automatically called by handle(), but you can use it
     * to boot all service providers when not handling a request.
     */
    public function boot() : self
    {
        if ($this->booted) {
            return $this;
        }
        
        $this->booted = true;
        
        foreach ($this->providers as $provider) {
            if (($provider instanceof BootableProviderInterface)) {
                $provider->boot($this);
            }
        }
               
        // ensure 'uSilex.responseEmitter' exists
        if (!isset($this['uSilex.responseEmitter'])) {
            $this['uSilex.responseEmitter'] = $this->protect(function ($response) {
                echo (string) $response->getBody();
            });
        }
        
        return $this;
    }

    
    /**
     * Handles the request and delivers the response.
     *
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }

    
    /**
     * Process the request and delivers the response with error management.
     *
     */
    public function run() : bool
    {
        try {
            // ensure boot
            $this->boot();
            $request = $this['uSilex.request'];
            $handler = $this['uSilex.httpHandler'];
            $response = $this->process($request, $handler);
           
            call_user_func($this['uSilex.responseEmitter'], $response, $this);
            
            $result = true;
        } catch (Exception $e) {
            if (isset($this['uSilex.exceptionHandler'])) {
                $response =  isset($request)
                    ? call_user_func($this['uSilex.exceptionHandler'], $e, $request)
                    : call_user_func($this['uSilex.exceptionHandler'], $e);
                call_user_func($this['uSilex.responseEmitter'], $response);
            } else {
                header('X-PHP-Response-Code: '.$e->getCode(), true, 500);
                echo $e->getMessage();
            }
            $result = false;
        }
          
        return $result;
    }
}
