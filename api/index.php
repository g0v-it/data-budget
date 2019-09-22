<?php
require_once __DIR__.'/vendor/autoload.php';

use uSilex\Application;
use LODMAP2D\ApplicationServiceProvider;
use EnvProvider\EnvProvider;

$app = new Application();

// Register all services dependences
$app->register(new ApplicationServiceProvider());

// Optional overload of the container attributes from environment variables
$app->register(new EnvProvider(), array(
    'env.prefix' => 'LODMAP2D_',
    'env.vars' => array(
        'backend' => 'env.cast.strval',	    // <= (int) LODMAP2D_BACKEND
    )
));

$app['env.overload']->run();
