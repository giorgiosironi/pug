<?php
class Introspector
{
    public function visualize($rootObject)
    {

        $fullyQualifiedClassName = get_class($rootObject);
        $reflectionObject = new ReflectionObject($rootObject);
        $properties = $reflectionObject->getProperties();
        if ($properties) {
            $directives = $this->propertiesToDirectives($rootObject, $properties);
        } else {
            $baseClassName = $this->getBasename($fullyQualifiedClassName);
            $directives = array("[$baseClassName]");
        }
        return implode("\n", $directives);
    }

    private function getBasename($fullyQualifiedClassName)
    {
        $position = strrpos($fullyQualifiedClassName, '\\');
        return substr($fullyQualifiedClassName, $position + 1);
    }

    private function propertiesToDirectives($object, $properties)
    {
        $baseClassName = $this->getBasename(get_class($object));
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyValue = $property->getValue($object);
            $propertyClass = get_class($propertyValue);
             $directives[] = "[$baseClassName]->[" . $this->getBasename($propertyClass) . "]";
        }
        return $directives;
    }
}
