<?php
require_once __DIR__.'/../vendor/autoload.php';
use uSilex\Application;
use uSilex\Psr11Trait;
use uSilex\Provider\Psr15\RelayServiceProvider as Psr15Provider;
use uSilex\Provider\Psr7\DiactorosServiceProvider as Psr7Provider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Diactoros\Response\TextResponse;

class MyMiddleware implements MiddlewareInterface
{
    use Psr11Trait;
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new \Zend\Diactoros\Response\TextResponse($this->get('message'));
    }
}

$app = new Application;
$app->register(new Psr15Provider());
$app->register(new Psr7Provider());
$app['myMiddleware'] = function ($app) {
    return new MyMiddleware($app);
};
$app['message'] = 'hello world!';
$app['handler.queue'] = ['myMiddleware'];
$app->run();

echo "\nmemory_get_usage: ".memory_get_usage();
echo "\nscript execution time:".(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]);
