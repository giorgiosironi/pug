<?php
namespace UmlReflector;

use Stubs\User;
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
    }

    public function testDisplaysASingleObjectAsAGraphWithOneNode()
    {
        $user = new User();
        $classDiagram = $this->introspector->visualize($user);
        $this->assertEquals('[User]', $classDiagram);
    }

    public function testDisplaysACoupleOfObjectsAsACompositionGraphWithTwoNodes()
    {
        $product = new Product(new ProductDescription);
        $classDiagram = $this->introspector->visualize($product);
        $this->assertEquals('[Product]->[ProductDescription]', $classDiagram);
    }

    public function testDisplaysParentAndChildObjectsAsAnInheritanceGraphWithTwoNodes()
    {
        $dog = new Dog();
        $classDiagram = $this->introspector->visualize($dog);
        $this->assertEquals('[Animal]^-[Dog]', $classDiagram);
    }

    public function testDisplaysAThreeLevelHierarchyAsAnInheritanceGraphWithThreeNodes()
    {
        $lassie = new Collie();
        $classDiagram = $this->introspector->visualize($lassie);
        $this->assertEquals("[Dog]^-[Collie]\n[Animal]^-[Dog]", $classDiagram);
    }
}
