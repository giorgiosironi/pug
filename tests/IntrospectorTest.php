<?php
use Stubs\User;
use Stubs\Product;
use Stubs\ProductDescription;

class IntrospectorTest extends PHPUnit_Framework_TestCase
{
    public function testDisplaysASingleObjectAsAGraphWithOneNode()
    {
        $user = new User();
        $introspector = new Introspector();
        $classDiagram = $introspector->visualize($user);
        $this->assertEquals('[User]', $classDiagram);
    }

    public function testDisplaysACoupleOfObjectsAsACompositionGraphWithTwoNodes()
    {
        $product = new Product(new ProductDescription);
        $introspector = new Introspector();
        $classDiagram = $introspector->visualize($product);
        $this->assertEquals('[Product]->[ProductDescription]', $classDiagram);
    }
}
