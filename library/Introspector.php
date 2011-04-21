<?php
class Introspector
{
    public function visualize($rootObject)
    {
        $class = get_class($rootObject);
        $class = $this->getBasename($class);
        return "[$class]";
    }

    private function getBasename($fullyQualifiedClassName)
    {
        $position = strrpos($fullyQualifiedClassName, '\\');
        return substr($fullyQualifiedClassName, $position + 1);
    }
}
