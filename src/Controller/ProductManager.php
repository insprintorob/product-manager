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
    public function indexAction(?string $sort = null, $args = []) : string
    {
        $skip = isset($args['skip']) ? intval($args['skip']) : null;
        $count = $this->productCollection->count();

        $projection = [
            'limit' => 10,
        ];

        if ($skip !== null) {
            $projection['skip'] = $skip;
        }

        if ($sort !== null) {
            $projection['sort'] = [ $sort => 1 ];
        }

        $cursor = $this->productCollection->find([], $projection);

        $products = $this->productFactory->createFromCursor($cursor);
        return $this->simpleView->render(VIEWS_DIR . '/index.phtml', [
            'products' => $products,
            'count' => $count
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
        $product = new Product();
        $files = $request->getUploadedFiles();
        $pictureFile = isset($files['picture']) ? $files['picture'] : null;

        if ($pictureFile && $pictureFile->file) {
            $picture = $this->uploadHelper->moveUploadedFile(UPLOADS_DIR, $pictureFile);
            $product->setPicture($picture);
        }

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $price = (float) $request->getParam('price');
        $description = $request->getParam('description');

        if (!$name || !$price || !is_numeric($price) || !$description) {
            return $response->withStatus(302)->wihHeader('Location', '/validation-error');
        }

        $product->setId($id);
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
    public function detailsAction(Response $response, $args) : string
    {
        $id = $args['id'];

        $productDocument = $this->productCollection->findOne([
            'id' => $id
        ]);

        $product = $this->productFactory->createFromBSON($productDocument);

        return $this->simpleView->render(VIEWS_DIR . '/details.phtml', [
            'product' => $product
        ]);
    }


    /**
     * Confirm deletion of a product
     */
    public function confirmDeleteAction($args) : string
    {
         return $this->simpleView->render(VIEWS_DIR . '/confirm-delete.phtml', [ 'id' => $args['id']]);
    }

    /**
     * Display a validation error
     */
    public function validationErrorAction() : string
    {
         return $this->simpleView->render(VIEWS_DIR . '/validation-error.phtml', []);
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
        $product = new Product();
        $files = $request->getUploadedFiles();
        $pictureFile = isset($files['picture']) ? $files['picture'] : null;

        if ($pictureFile && $pictureFile->file) {
            $picture = $this->uploadHelper->moveUploadedFile(UPLOADS_DIR, $pictureFile);
            $product->setPicture($picture);
        }

        $name = $request->getParam('name');
        $price = (float) $request->getParam('price');
        $description = $request->getParam('description');

        if (!$name || !$price || !is_numeric($price) || !$description) {
            return $response->withStatus(302)->withHeader('Location', '/validation-error');
        }

        $product->setId(uniqid());
        $product->setName($name);
        $product->setPrice($price);
        $product->setDescription($description);

        $this->productCollection->insertOne($product->serializeToObject());

        return $response->withStatus(302)->withHeader('Location', '/');
    }
}