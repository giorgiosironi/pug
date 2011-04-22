<?php
namespace Stubs;

class Shop
{
    private $product;
    
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
