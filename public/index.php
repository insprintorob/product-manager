<?php

use Slim\App;

define('ROOT_DIR', realpath(__DIR__ . '/../')); // Makes including other stuff simpler, everything can be included from the same place

// Set up the composer autoloader
require(ROOT_DIR . '/vendor/autoload.php');

// Set up Slim App
$app = new App([]);

// Define routes. Could be done in a seperate file for modularity but I've but them here to keep things simple
$app->get('/', function($request, $response, $args) {
    return $response->write('test');
});

$app->run();
