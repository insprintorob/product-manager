<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use ProductManager\Product;
use ProductManager\Controller\ProductManager;

/**
 * New mocks are created for each test
 */
class ProductManagerTest extends TestCase {
    public function testIndexAction() {
        $productCollection = \Mockery::mock('MongoDB\Collection');
        $productFactory = \Mockery::mock('ProductManager\ProductFactory');
        $uploadHelper = \Mockery::mock('ProductManager\UploadHelper');
        $simpleView = \Mockery::mock('ProductManager\SimpleView');

        $productCollection->shouldReceive('find')->once()->andReturn([]);
        $productFactory->shouldReceive('createFromCursor')->once()->andReturn([
            new Product()
        ]);

        $simpleView->shouldReceive('render')->once()->andReturn('test');

        $productManager = new ProductManager(
            $productCollection,
            $productFactory,
            $uploadHelper,
            $simpleView
        );

        $output = $productManager->indexAction();
        $this->assertEquals('test', $output);
    }

    public function testCreateAction() {
        $productCollection = \Mockery::mock('MongoDB\Collection');
        $productFactory = \Mockery::mock('ProductManager\ProductFactory');
        $uploadHelper = \Mockery::mock('ProductManager\UploadHelper');
        $simpleView = \Mockery::mock('ProductManager\SimpleView');
        $simpleView->shouldReceive('render')->once()->andReturn('test');

        $productManager = new ProductManager(
            $productCollection,
            $productFactory,
            $uploadHelper,
            $simpleView
        );

        $output = $productManager->createAction();
        $this->assertEquals('test', $output);
    }

    public function testPostEditAction() {
        $productCollection = \Mockery::mock('MongoDB\Collection');
        $productFactory = \Mockery::mock('ProductManager\ProductFactory');
        $uploadHelper = \Mockery::mock('ProductManager\UploadHelper');
        $simpleView = \Mockery::mock('ProductManager\SimpleView');
        $simpleView->shouldReceive('render')->once()->andReturn('test');
        $response = \Mockery::mock('Slim\Http\Response');
        $request = \Mockery::mock('Slim\Http\Request');

        $request->shouldReceive('getUploadedFiles')->once()->andReturn([
            'picture' => \Mockery::mock('Slim\Http\UploadedFile')
        ]);

        $uploadHelper->shouldReceive('moveUploadedFile')->once();

        $request->shouldReceive('getParam')->once()->with('id')->andReturn('1');
        $request->shouldReceive('getParam')->once()->with('name')->andReturn('Tesla solar roof tile');
        $request->shouldReceive('getParam')->once()->with('price')->andReturn('100.00');
        $request->shouldReceive('getParam')->once()->with('description')->andReturn('Solar panels that look like normal roof tiles');

        $productCollection->shouldReceive('updateOne')->once();

        $response->shouldReceive('withStatus')->once()->with(302)->andReturn($response);
        $response->shouldReceive('withHeader')->once()->andReturn($response);

        $productManager = new ProductManager(
            $productCollection,
            $productFactory,
            $uploadHelper,
            $simpleView
        );

        $response = $productManager->postEditAction($request, $response);
        $this->assertNotNull($response);
    }
}