<?php

namespace Jatmy\Framework\Container;

use Jatmy\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];
    public function add(string $id, string|object $service = null)
    {
        if(is_null($service)) {
            if(!class_exists($id)) {
                throw new ContainerException("Class {$id} not found");
            }
            $service = $id;
        }
        $this->services[$id] = $service;
    }
    public function get(string $id)
    {
        return new $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
