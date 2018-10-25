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
}