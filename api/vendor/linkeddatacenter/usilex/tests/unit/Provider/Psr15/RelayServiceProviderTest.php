<?php
namespace uSilex\Tests;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use uSilex\Application;
use uSilex\Provider\Psr15\RelayServiceProvider;

class RelayServiceProviderTest extends TestCase
{
    public function testRegistration()
    {
        $app = new Application;
        $app->register(new RelayServiceProvider());
        $this->assertTrue(isset($app['uSilex.httpHandler']));
        $this->assertTrue(isset($app['handler.queue']));
        $this->assertTrue(isset($app['relay.pimpleResolver']));
        $this->assertTrue(isset($app['relay.factory']));
        $this->assertTrue(is_array($app['handler.queue']));
        $this->assertInstanceOf('\\Relay\\Relay', $app['uSilex.httpHandler']);
    }
}
