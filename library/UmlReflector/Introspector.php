<?php
namespace UmlReflector;

class Introspector
{
    /**
     * @param object $rootObject
     * @return string yUML code
     */
    public function visualize($rootObject)
    {
        $reflectionObject = new \ReflectionObject($rootObject);
        $directives = new Directives();
        $this->classNameToDirectives($directives, $reflectionObject);
        $this->propertiesToDirectives($directives, $reflectionObject, $rootObject);
        $this->hierarchyToDirectives($directives, $reflectionObject);
        return $directives->toString();
    }

    private function getBasename($fullyQualifiedClassName)
    {
        $position = strrpos($fullyQualifiedClassName, '\\');
        return substr($fullyQualifiedClassName, $position + 1);
    }

    private function classNameToDirectives(Directives $directives, \ReflectionObject $reflectionObject)
    {
        $fullyQualifiedClassName = $reflectionObject->getName();
        $baseClassName = $this->getBasename($fullyQualifiedClassName);
        $directives->addClass($baseClassName);
    }

    private function propertiesToDirectives(Directives $directives, \ReflectionObject $reflectionObject, $rootObject)
    {
        $properties = $reflectionObject->getProperties();
        $baseClassName = $this->getBasename($reflectionObject->getName());
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $propertyValue = $property->getValue($rootObject);
            $propertyClass = $this->getBasename(get_class($propertyValue));
            $directives->addComposition($baseClassName, $propertyClass);
        }
    }

    private function hierarchyToDirectives(Directives $directives, \ReflectionObject $object)
    {
        $parentClass = $object->getParentClass();
        $currentClass = $object;
        while ($parentClass) {
            $parentClassName = $this->getBasename($parentClass->getName());
            $childClassName = $this->getBasename($currentClass->getName());
            $directives->addInheritance($parentClassName, $childClassName); 
            $currentClass = $parentClass;
            $parentClass = $parentClass->getParentClass();
        }
    }
}
