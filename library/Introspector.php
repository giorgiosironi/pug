<?php
class Introspector
{
    public function visualize($rootObject)
    {

        $fullyQualifiedClassName = get_class($rootObject);
        $baseClassName = $this->getBasename($fullyQualifiedClassName);
        $reflectionObject = new ReflectionObject($rootObject);
        $directives = array();
        $properties = $reflectionObject->getProperties();
        if ($properties) {
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $propertyValue = $property->getValue($rootObject);
                $propertyClass = get_class($propertyValue);
                 $directives[] = "[$baseClassName]->[" . $this->getBasename($propertyClass) . "]";
            }
        } else {
            $directives[] = "[$baseClassName]";
        }
        return implode("\n", $directives);
    }

    private function getBasename($fullyQualifiedClassName)
    {
        $position = strrpos($fullyQualifiedClassName, '\\');
        return substr($fullyQualifiedClassName, $position + 1);
    }
}
