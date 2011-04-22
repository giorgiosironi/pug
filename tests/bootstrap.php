<?php
require_once __DIR__ . '/../library/UmlReflector/SplClassLoader.php';
$classLoader = new UmlReflector\SplClassLoader('UmlReflector', __DIR__ . '/../library');
$classLoader->register();
$classLoader = new UmlReflector\SplClassLoader('Stubs', __DIR__);
$classLoader->register();
$classLoader = new UmlReflector\SplClassLoader('OtherStubs', __DIR__);
$classLoader->register();
