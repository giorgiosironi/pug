<?php
namespace UmlReflector;

class Introspector
{
    private $examinedClassNames = array();

    /**
     * @param object $rootObject
     * @return string yUML code
     */
    public function visualize($rootObject, Directives $directives)
    {
        $reflectionObject = new \ReflectionObject($rootObject);
        if (in_array($reflectionObject->getName(), $this->examinedClassNames)) {
            return;
        }
        $this->examinedClassNames[] = $reflectionObject->getName();
        $this->classNameToDirectives($directives, $reflectionObject);
        $this->propertiesToDirectives($directives, $reflectionObject, $rootObject);
        $this->hierarchyToDirectives($directives, $reflectionObject);
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
            $collaborator = $property->getValue($rootObject);
            if (!is_object($collaborator)) {
                continue;
            }
            $propertyClass = $this->getBasename(get_class($collaborator));
            $directives->addComposition($baseClassName, $propertyClass);
            $this->visualize($collaborator, $directives);
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
