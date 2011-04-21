<?php
class Directives
{
    private $classes = array();
    private $compositionsSources = array();
    private $compositionTargets = array();

    public function addClass($className)
    {
        $this->classes[] = $className;
    }

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
        }, $this->classes);
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
