<?php
namespace Stubs;

class Driver
{
    private $car;
    
    public function __construct(Car $car)
    {
        $this->car = $car;
    }
}
