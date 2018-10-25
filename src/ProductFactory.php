<?php
namespace ProductManager;

class ProductFactory {
    /**
     * Create a Product from a MongoDB document
     */
    public function createFromObject(array $document) {

    }

    /**
     * Create and return array of Products using documents from a MongoDB query result
     *
     * @param documents
     * @return array
     */
    public function createFromCursor($cursor) : array {
        $products = [];

        foreach ($cursor as $document) {
            $product = new Product();
            $product->setId($document['id']);
            $product->setPicture($document['picture']);
            $product->setName($document['name']);
            $product->setPrice($document['price']);
            $product->setDescription($document['description']);

            $products[] = $product;
        }

        return $products;
    }
}