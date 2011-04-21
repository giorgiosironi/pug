<?php
namespace Stubs;

class Product
{
    private $description;
    
    public function __construct(ProductDescription $description)
    {
        $this->description = $description;
    }
}
