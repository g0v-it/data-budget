<?php
namespace uSilex\Tests;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use uSilex\Application;
use uSilex\Provider\Psr7\GuzzleServiceProvider;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use Exception;

class GuzzleServiceProviderTest extends TestCase
{
    public function testRegistration()
    {
        $app = new Application;
        $app->register(new GuzzleServiceProvider());
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
        $app->register(new GuzzleServiceProvider());
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
        $app->register(new GuzzleServiceProvider());
        $response = new Response(200, [], 'ok response');
        $app['uSilex.responseEmitter']($response, $app);
        $this->expectOutputString('ok response');
    }
}
