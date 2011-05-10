PUG (PHP UML Generator) is a tool which generates an Uml class diagram from a live PHP object graph.
Up to now, it generates code for the yUML tool (yuml.me), not directly pictures.

- Usage
<?php
require_once 'tests/bootstrap.php';

class SampleTest extends PHPUnit_Framework_TestCase
{
    public function testGenerationOfyUMLCode()
    {
        $introspector = new UmlReflector\Introspector;
        $directives = new UmlReflector\Directives;
        $object = new stdClass; // substitute with your root object
        $introspector->visualize($object, $directives);
        // code for yUML
        var_dump($directives->toString());
    }
}
