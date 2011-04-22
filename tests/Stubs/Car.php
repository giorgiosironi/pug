<?php
namespace Stubs;

class Car
{
    private $driver;
    
    public function setDriver(Driver $driver)
    {
        $this->driver = $driver;
    }
}
