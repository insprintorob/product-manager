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
}