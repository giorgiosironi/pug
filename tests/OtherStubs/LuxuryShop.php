<?php
namespace OtherStubs;
use Stubs\Product;

class LuxuryShop
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
