<?php
namespace ProductManager\Controller;


use ProductManager\SimpleView;
use ProductManager\Product;
use Slim\Http\Request;
use MongoDB\Client;
use MongoDB\Collection;
use Slim\Http\Response;
use ProductManager\ProductFactory;
use ProductManager\UploadHelper;

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
     * @var UploadHelper
     */
    private $uploadHelper;

    /**
     * @var SimpleView
     */
    private $simpleView;

    public function __construct(
        Collection $productCollection,
        ProductFactory $productFactory,
        UploadHelper $uploadHelper,
        SimpleView $simpleView
    ) {
        $this->productCollection = $productCollection;
        $this->productFactory = $productFactory;
        $this->uploadHelper = $uploadHelper;
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
    public function editAction(Response $response, $args) : string
    {
        $id = $args['id'];

        $productDocument = $this->productCollection->findOne([
            'id' => $id
        ]);

        $product = $this->productFactory->createFromBSON($productDocument);

        return $this->simpleView->render(VIEWS_DIR . '/edit.phtml', [
            'product' => $product
        ]);
    }

    public function postEditAction(Request $request, Response $response) : Response
    {
        $files = $request->getUploadedFiles();
        $pictureFile = $files['picture'];
        $picture = $this->uploadHelper->moveUploadedFile(UPLOADS_DIR, $pictureFile);

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $price = $request->getParam('price');
        $description = $request->getParam('description');

        $product = new Product();
        $product->setId($id);
        $product->setPicture($picture); // todo: Implement
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);

        $this->productCollection->updateOne(
            [
                'id' => $id,
            ],
            [
                '$set' => $product->serializeToObject(),
            ]
        );

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Product details page
     */
    public function detailsAction() : string
    {
        return $this->simpleView->render(VIEWS_DIR . '/details.phtml', []);
    }

    /**
     * Confirm deletion of a product
     */
    public function confirmDeleteAction($args) : string
    {
         return $this->simpleView->render(VIEWS_DIR . '/confirm-delete.phtml', [ 'id' => $args['id']]);
    }

    /**
     * Delete a product
     */
    public function deleteAction(Response $response, $args) : Response {
        $id = $args['id'];

        $this->productCollection->deleteOne([
            'id' => $id
        ]);

        return $response->withStatus(302)->withHeader('Location', '/');
    }

    /**
     * Process the creation of a product
     */
    public function postCreateAction(Request $request, Response $response) : Response
    {
        $files = $request->getUploadedFiles();
        $pictureFile = $files['picture'];
        $picture = $this->uploadHelper->moveUploadedFile(UPLOADS_DIR, $pictureFile);
        $name = $request->getParam('name');
        $price = $request->getParam('price');
        $description = $request->getParam('description');

        $product = new Product();
        $product->setId(uniqid());
        $product->setPicture($picture); // todo: Implement
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);

        $this->productCollection->insertOne($product->serializeToObject());

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}