<?php
use Stubs\User as User;

class IntrospectorTest extends PHPUnit_Framework_TestCase
{
    public function testDisplaysASingleObjectAsAGraphWithOneNode()
    {
        $user = new User();
        $introspector = new Introspector();
        $classDiagram = $introspector->visualize($user);
        $this->assertEquals('[User]', $classDiagram);
    }
}
