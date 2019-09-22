<?php
namespace uSilex\Tests;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use uSilex\Application;
use uSilex\Provider\Psr7\DiactorosServiceProvider;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\Response\JsonResponse;
use Exception;

class DiactorosServiceProviderTest extends TestCase
{
    public function testRegistration()
    {
        $app = new Application;
        $app->register(new DiactorosServiceProvider());
        $this->assertTrue(isset($app['uSilex.request']));
        $this->assertTrue(isset($app['uSilex.responseEmitter']));
        $this->assertTrue(isset($app['uSilex.exceptionHandler']));
        $this->assertInstanceOf('\\Psr\\Http\\Message\\ServerRequestInterface', $app['uSilex.request']);
        $this->assertTrue(is_callable($app['uSilex.responseEmitter']));
        $this->assertTrue(is_callable($app['uSilex.exceptionHandler']));
    }
    
    
    public function testexceptionHandler()
    {
        $app = new Application;
        $app->register(new DiactorosServiceProvider());
        $e = new Exception('test exception');
        $response = $app['uSilex.exceptionHandler']($e, $app);
        $this->assertInstanceOf('\\Psr\\Http\\Message\\ResponseInterface', $response);
    }
    
    
    /**
     * @runInSeparateProcess
     */
    public function testResponseEmitter()
    {
        $app = new Application;
        $app->register(new DiactorosServiceProvider());
        $response = new TextResponse('ok response');
        $app['uSilex.responseEmitter']($response, $app);
        $this->expectOutputString('ok response');
    }
}
