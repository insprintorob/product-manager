<?php
// Define some helpful constants
define('ROOT_DIR', realpath(__DIR__ . '/../')); // Makes including other stuff simpler, everything can be included from the same place
define('VIEWS_DIR', ROOT_DIR . '/views');

// Set up the composer autoloader and import namespaces
require(ROOT_DIR . '/vendor/autoload.php');
use Slim\App;
use ProductManager\Controller\ProductManager;
use ProductManager\SimpleView;
use Slim\Container;
use Slim\Http\Request;
use MongoDB\Client;
use ProductManager\ProductFactory;

// Set up the DI container using Factory Functions
$container = new Container();

$container['simple-view'] = function() {
    return new SimpleView();
};

$container['mongodb-client'] = function() {
    $client = new Client('mongodb://127.0.0.1:27017');
    return $client;
};

$container['product-factory'] = function() {
    $productFactory = new ProductFactory();
    return $productFactory;
};

// Expose product collection as a dependency
$container['product-collection'] = function() use ($container) {
    $client = $container['mongodb-client'];
    $productCollection = $client->productManager->products;
    return $productCollection;
};

$container['product-manager-controller'] = function() use ($container) {
    $productCollection = $container->get('product-collection');
    $productFactory = $container->get('product-factory');
    $simpleView = $container->get('simple-view');

    return new ProductManager(
        $productCollection,
        $productFactory,
        $simpleView
    );
};

// Initialize the Slim App
$app = new App($container);

// Define the routes. Could be done in a seperate file for modularity but I've but them here to keep things simple
$app->get('/', function($request, $response, $args) use ($container) {
    $productManagerController = $container->get('product-manager-controller');
    $response->write($productManagerController->indexAction());
});

$app->get('/create', function($request, $response, $args) use ($container) {
    $productManagerController = $container->get('product-manager-controller');
    $response->write($productManagerController->createAction());
});

$app->post('/create', function($request, $response, $args) use ($container) {
    $productManagerController = $container->get('product-manager-controller');
    return $productManagerController->postCreateAction($request, $response);
});

$app->get('/edit/{id}', function($request, $response, $args) use ($container) {
    $productManagerController = $container->get('product-manager-controller');
    $response->write($productManagerController->editAction($response, $args));
});

$app->post('/edit', function($request, $response, $args) use ($container) {
    $productManagerController = $container->get('product-manager-controller');
    return $productManagerController->postEditAction($request, $response);
});

$app->get('/delete/{id}', function($request, $response, $args) use ($container) {
    $productManagerController = $container->get('product-manager-controller');
    return $productManagerController->deleteAction($response, $args);
});


$app->run();