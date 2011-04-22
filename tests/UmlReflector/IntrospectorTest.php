<?php
namespace UmlReflector;

use Stubs\User;
use Stubs\Shop;
use Stubs\Product;
use Stubs\ProductDescription;
use Stubs\Dog;
use Stubs\Collie;
use Stubs\Driver;
use Stubs\Car;
use Stubs\Country;

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
        $this->assertResultIs('[User]');
    }

    public function testDisplaysACoupleOfObjectsAsACompositionGraphWithTwoNodes()
    {
        $product = new Product(new ProductDescription);
        $this->visualize($product);
        $this->assertResultIs('[Product]->[ProductDescription]');
    }

    public function testDisplaysMultipleLevelsOfComposition()
    {
        $shop = new Shop(new Product(new ProductDescription));
        $this->visualize($shop);
        $this->assertResultIs("[Shop]->[Product]\n[Product]->[ProductDescription]");
    }

    public function testDisplaysMultipleLevelsOfCompositionWithoutFallingIntoInfiniteRecursion()
    {
        $driver = new Driver($car = new Car);
        $car->setDriver($driver);
        $this->visualize($car);
        $this->assertResultIs("[Car]->[Driver]\n[Driver]->[Car]");
        $this->markTestIncomplete("Result should be improved to exploit `-` relationship.");
    }

    public function testDisplaysParentAndChildObjectsAsAnInheritanceGraphWithTwoNodes()
    {
        $dog = new Dog();
        $this->visualize($dog);
        $this->assertResultIs('[Animal]^-[Dog]');
    }

    public function testDisplaysAThreeLevelHierarchyAsAnInheritanceGraphWithThreeNodes()
    {
        $lassie = new Collie();
        $this->visualize($lassie);
        $this->assertResultIs("[Dog]^-[Collie]\n[Animal]^-[Dog]");
    }

    public function testSkipsScalarFields()
    {
        $this->visualize(new Country("Italy"));
        $this->assertResultIs("[Country]");
    }

    public function testBaseClassNamesAreJustClassNamesForNonNamespacedClasses()
    {
        $this->visualize(new \stdClass);
        $this->assertResultIs("[stdClass]");
    }

    public function testCanSkipLibraryNamespaces()
    {
        $this->introspector->addSkippedNamespace('Stubs');
        $shop = new \OtherStubs\LuxuryShop(new Product(new ProductDescription));
        $this->visualize($shop);
        $this->assertResultIs("[LuxuryShop]->[Product]");
    }

    private function assertResultIs($yumlCode)
    {
        $this->assertEquals($yumlCode, $this->directives->toString());
    }
}
