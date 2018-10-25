<?php
namespace ProductManager\Controller;


use ProductManager\SimpleView;

class ProductManager {
    /**
     * @var SimpleView
     */
    private $simpleView;

    public function __construct(
        SimpleView $simpleView
    ) {
        $this->simpleView = $simpleView;
    }

    /**
     * Product listing page
     */
    public function indexAction() : string
    {
        return $this->simpleView->render(VIEWS_DIR . '/index.phtml', []);
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
     * Upsert (insert or update) a Product
     */
    public function upsertAction() : string
    {
        header('Location : /');
    }
}