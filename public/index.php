<?php

// Set up the composer autoloader
define('ROOT_DIR', realpath(__DIR__ . '/../')); // Makes including other stuff simpler, everything can be included from the same place
require(ROOT_DIR . '/vendor/autoload.php');
define('VIEWS_DIR', ROOT_DIR . '/views');
use Slim\App;
use ProductManager\Controller\ProductManager;
use ProductManager\SimpleView;
use Slim\Container;

// Set up DI container using Factory Functions
$container = new Container();

$container['simple-view'] = function() {
    return new SimpleView();
};

$container['product-manager-controller'] = function() use ($container) {
    $simpleView = $container->get('simple-view');
    return new ProductManager($simpleView);
};

// Initialize Slim App
$app = new App($container);

// Define routes. Could be done in a seperate file for modularity but I've but them here to keep things simple
$app->get('/', function($request, $response, $args) use ($container) {
    $productManagerController = $container->get('product-manager-controller');
    $response->write($productManagerController->indexAction());
});

$app->run();