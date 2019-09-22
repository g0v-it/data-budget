<?php
namespace uSilex\Tests;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use uSilex\Application;
use uSilex\Provider\Psr15\ZendPipeServiceProvider;

class ZendPipeServiceProviderTest extends TestCase
{
    public function testRegistration()
    {
        $app = new Application;
        $app->register(new ZendPipeServiceProvider());
        $this->assertTrue(isset($app['uSilex.httpHandler']));
        $this->assertTrue(isset($app['handler.queue']));
        $this->assertTrue(isset($app['piper']));
        $this->assertInstanceOf('\\Zend\\Stratigility\\MiddlewarePipe', $app['uSilex.httpHandler']);
        $this->assertEquals($app['uSilex.httpHandler'], $app['piper']);
    }
}
