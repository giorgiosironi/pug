<?php
namespace UmlReflector;

use Stubs\User;
use Stubs\Shop;
use Stubs\Product;
use Stubs\ProductDescription;
use Stubs\Dog;
use Stubs\Collie;

class IntrospectorTest extends \PHPUnit_Framework_TestCase
{
    private $introspector;

    public function setUp()
    {
        $this->introspector = new Introspector();
        $this->directives = new Directives();
    }

    private function visualize($object)
    {
        $this->introspector->visualize($object, $this->directives);
    }

    public function testDisplaysASingleObjectAsAGraphWithOneNode()
    {
        $user = new User();
        $this->visualize($user);
        $this->assertEquals('[User]', $this->directives->toString());
    }

    public function testDisplaysACoupleOfObjectsAsACompositionGraphWithTwoNodes()
    {
        $product = new Product(new ProductDescription);
        $this->visualize($product);
        $this->assertEquals('[Product]->[ProductDescription]', $this->directives->toString());
    }

    public function testDisplaysMultipleLevelsOfComposition()
    {
        $shop = new Shop(new Product(new ProductDescription));
        $this->visualize($shop);
        $this->assertEquals("[Shop]->[Product]\n[Product]->[ProductDescription]", $this->directives->toString());
    }

    public function testDisplaysParentAndChildObjectsAsAnInheritanceGraphWithTwoNodes()
    {
        $dog = new Dog();
        $this->visualize($dog);
        $this->assertEquals('[Animal]^-[Dog]', $this->directives->toString());
    }

    public function testDisplaysAThreeLevelHierarchyAsAnInheritanceGraphWithThreeNodes()
    {
        $lassie = new Collie();
        $this->visualize($lassie);
        $this->assertEquals("[Dog]^-[Collie]\n[Animal]^-[Dog]", $this->directives->toString());
    }
}
