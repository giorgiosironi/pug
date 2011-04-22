<?php
namespace UmlReflector;

class Directives
{
    private $classes = array();
    private $compositionsSources = array();
    private $compositionTargets = array();
    private $inheritanceParents = array();
    private $inheritanceChildren = array();

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

    public function addInheritance($parentClass, $childClass)
    {
        $this->inheritanceParents[] = $parentClass;
        $this->inheritanceChildren[] = $childClass;
    }

    public function toString()
    {
        return implode("\n", array_merge(
            $this->classesDirectives(),
            $this->compositionDirectives(),
            $this->inheritanceDirectives()
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

    private function inheritanceDirectives()
    {
        $inheritanceDirectives = array();
        foreach ($this->inheritanceParents as $i => $parentClass) {
            $childClass = $this->inheritanceChildren[$i];
            $inheritanceDirectives[] = "[$parentClass]^-[$childClass]";
        }
        return $inheritanceDirectives;
    }
}
