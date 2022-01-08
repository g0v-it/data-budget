#!/usr/bin/env php
<?php
/**
 * Simple command line utility to interface the  BDAP class
 **/

require_once __DIR__.'/vendor/autoload.php';

$usage = "
bdap util usage:
    bdap.php -u <ckan id> : returns the api endpoint for the ckan json metadata 
";

( $argc == 3 ) || die($usage);


switch ($argv[1]) {
    case '-u':
        echo \MEF\BDAP::CKAN_DATASET . $argv[2];
        exit;
    ;

    default:
        die($usage);
    break;
}
