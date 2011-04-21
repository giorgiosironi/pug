<?php
class Directives
{
    public function addClass($className)
    {
        $this->classes[] = $className;
    }

    public function toString()
    {
        return implode("\n", $this->classesDirectives());
    }

    private function classesDirectives()
    {
        return array_map(function($className) {
            return "[$className]";
        }, $this->classes);
    }
}
