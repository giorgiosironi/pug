<?php
namespace UmlReflector;

class Directives
{
    private $classes = array();
    private $compositionsSources = array();
    private $compositionTargets = array();

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

    public function toString()
    {
        return implode("\n", array_merge(
            $this->classesDirectives(),
            $this->compositionDirectives()
        ));
    }

    private function classesDirectives()
    {
        return array_map(function($className) {
            return "[$className]";
        }, array_filter($this->classes, array($this, 'isNotAlreadyPresentInCompositions')));
    }

    public function isNotAlreadyPresentInCompositions($className) {
        return !(in_array($className, $this->compositionsSources) || in_array($className, $this->compositionTargets));
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
}
