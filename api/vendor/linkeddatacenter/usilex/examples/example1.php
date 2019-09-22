<?php
namespace EXAMPLE;

require_once __DIR__.'/../vendor/autoload.php';
include "MyMiddleware.php"; // here your MyMiddleware class definition
$app = new \uSilex\Application;
$app['uSilex.request'] = \Zend\Diactoros\ServerRequestFactory::fromGlobals();
$app['uSilex.responseEmitter'] = $app->protect(function ($response) {
    echo $response->getBody();
});
$app['uSilex.httpHandler'] = function ($app) {
    return new \Relay\Relay([new MyMiddleware($app)]);
};
$app->run();

echo "\n<pre>";
echo "\nmemory_get_usage: ".memory_get_usage();
echo "\nscript execution time:".(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]);
echo "<pre>";
