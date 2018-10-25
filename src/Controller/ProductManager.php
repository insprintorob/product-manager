<?php
namespace ProductManager\Controller;


use ProductManager\SimpleView;
use ProductManager\Product;
use Slim\Http\Request;
use MongoDB\Client;
use MongoDB\Collection;
use Slim\Http\Response;
use ProductManager\ProductFactory;

class ProductManager {
    /**
     * @var Collection
     */
    private $productCollection;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var SimpleView
     */
    private $simpleView;

    public function __construct(
        Collection $productCollection,
        ProductFactory $productFactory,
        SimpleView $simpleView
    ) {
        $this->productCollection = $productCollection;
        $this->productFactory = $productFactory;
        $this->simpleView = $simpleView;
    }

    /**
     * Product listing page
     */
    public function indexAction() : string
    {
        $products = $this->productFactory->createFromCursor($this->productCollection->find([]));
        return $this->simpleView->render(VIEWS_DIR . '/index.phtml', [
            'products' => $products
        ]);
    }

    /**
     * Create a product
     */
    public function createAction() : string
    {
        return $this->simpleView->render(VIEWS_DIR . '/create.phtml', []);
    }

    /**
     * Edit a product
     */
    public function editAction() : string
    {
        return $this->simpleView->render(VIEWS_DIR . '/edit.phtml', []);
    }

    /**
     * Product details page
     */
    public function detailsAction() : string
    {
        return $this->simpleView->render(VIEWS_DIR . '/details.phtml', []);
    }

    /**
     * Process the creation of a product
     */
    public function postCreateAction(Request $request, Response $response) : Response
    {
        $name = $request->getParam('name');
        $price = $request->getParam('price');
        $description = $request->getParam('description');

        $product = new Product();
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);

        $this->productCollection->insertOne($product->serializeToObject());

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}