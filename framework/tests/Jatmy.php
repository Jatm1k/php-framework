<?php
namespace Jatmy\Framework\Tests;

class Jatmy
{
    public function __construct(private readonly Food $food)
    {
        
    }

    public function getFood(): Food
    {
        return $this->food;
    }
}
