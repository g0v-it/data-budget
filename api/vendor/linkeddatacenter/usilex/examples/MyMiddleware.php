<?php
namespace EXAMPLE;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

class MyMiddleware implements MiddlewareInterface
{
    use \uSilex\Psr11Trait;
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Return a text message in the container or a generic greeting...
        $message = $this->has('message') ? $this->get('message') : 'Hi';
        
        return new \Zend\Diactoros\Response\TextResponse($message);
    }
}
