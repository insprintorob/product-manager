<?php
namespace ProductManager;

class Product {
    /**
     * @var string
     */
    private $picture;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $description;

    public function getPicture() : ?string {
        return $this->picture;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPrice() : float {
        return $this->price;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function setPicture(?string $picture) : void {
        $this->picture = $picture;
    }

    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function setPrice(float $price) : void {
        $this->price = $price;
    }

    public function setDescription(string $description) : void {
        $this->description = $description;
    }

    /**
     * Serialize to an object
     * Can be used to insert an instance of this class into MongoDB
     */
    public function serializeToObject() {
        $product = new \stdClass();
        $product->picture = $this->picture;
        $product->name = $this->name;
        $product->price = $this->price;
        $product->description = $this->description;

        return $product;
    }
}