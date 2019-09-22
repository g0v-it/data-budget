<?php
namespace EXAMPLE;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/example4/StratigilityServiceProvider.php';
include "MyMiddleware.php";

use uSilex\Application;
use uSilex\Provider\Psr15\ZendPipeServiceProvider;
use uSilex\Provider\Psr7\DiactorosServiceProvider;

$app = new Application;
$app->register(new ZendPipeServiceProvider());
$app->register(new DiactorosServiceProvider());
$app->register(new StratigilityServiceProvider);

$app['basepath'] = '/examples/example4.php';
$app['message'] = 'Hello World';
$app['handler.queue'] = [
    'home_page',
    'hello_page',
    'default_page'
];

$app->run();

echo "\n<pre>";
echo "\nmemory_get_usage: ".memory_get_usage();
echo "\nscript execution time:".(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]);
echo "\n</pre>";
