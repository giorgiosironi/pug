<?php
namespace UmlReflector;

class Directives
{
    private $classes = array();
    private $compositionsSources = array();
    private $compositionTargets = array();
    private $inheritanceParents = array();
    private $inheritanceChildren = array();
    private $implementors = array();
    private $interfaces = array();

    /**
     * @param string $className     Class that should be represented in the diagram
     * @return void
     */
    public function addClass($className)
    {
        $this->classes[] = $className;
    }

    /**
     * @param string $sourceClassName   class containing the field
     * @param string $targetClassName   class pointed
     * @return void
     */
    public function addComposition($sourceClassName, $targetClassName)
    {
        $this->compositionsSources[] = $sourceClassName;
        $this->compositionTargets[] = $targetClassName;
    }
    
    /**
     * @param string $implementor
     * @param string $interface
     * @return void
     */
    public function addInterface($implementor, $interface)
    {
        $this->implementors[] = $implementor;
        $this->interfaces[] = $interface;
    }

    public function addInheritance($parentClassName, $childClassName)
    {
        $this->inheritanceParents[] = $parentClassName;
        $this->inheritanceChildren[] = $childClassName;
    }

    public function toString()
    {
        return implode("\n", array_merge(
            $this->classesDirectives(),
            $this->compositionDirectives(),
            $this->inheritanceDirectives(),
            $this->interfaceDirectives()
        ));
    }

    private function classesDirectives()
    {
        return array_map(function($className) {
            return "[$className]";
        }, array_filter($this->classes, array($this, 'isNotAlreadyPresentInRelationships')));
    }

    public function isNotAlreadyPresentInRelationships($className) {
        return !(in_array($className, $this->compositionsSources)
              || in_array($className, $this->compositionTargets)
              || in_array($className, $this->inheritanceParents)
              || in_array($className, $this->inheritanceChildren)
        );
    }

    private function compositionDirectives()
    {
        $compositionDirectives = array();
        foreach ($this->compositionsSources as $i => $sourceClassName) {
            $targetClassName = $this->compositionTargets[$i];
            $compositionDirectives[] = "[$sourceClassName]->[$targetClassName]";
        }
        return $compositionDirectives;
    }
    
    private function interfaceDirectives()
    {
        $interfaceDirectives = array();
        foreach ($this->implementors as $i => $parentClassName) {
            $childClassName = $this->interfaces[$i];
            $interfaceDirectives[] = "[$parentClassName]-.-^[$childClassName]";
        }
        return $interfaceDirectives;
    }

    private function inheritanceDirectives()
    {
        $inheritanceDirectives = array();
        foreach ($this->inheritanceParents as $i => $parentClassName) {
            $childClassName = $this->inheritanceChildren[$i];
            $inheritanceDirectives[] = "[$parentClassName]^-[$childClassName]";
        }
        return $inheritanceDirectives;
    }
}
