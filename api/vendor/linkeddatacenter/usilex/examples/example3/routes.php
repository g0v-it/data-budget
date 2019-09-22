<?php
/**
 * Here your routes
 */

$routeMap->get('home', '/', function () {
    return "This is the home. Try '/hello'";
});

$routeMap->get('hello', '/hello', function () {
    return "Hello. Try '/hello/world'";
});
    
$routeMap->get('hello_name', '/hello/{name}', function ($request) {
    return "Hello ".$request->getAttribute('name');
});
