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
        if(!$this->has($id)) {
            if(!class_exists($id)) {
                throw new ContainerException("Service {$id} not found");
            }
            $this->add($id);
        }
        return $this->resolve($this->services[$id]);
    }

    private function resolve($service)
    {
        $reflection = new \ReflectionClass($service);
        $contstructor = $reflection->getConstructor();
        if(is_null($contstructor)) {
            return $reflection->newInstance();
        }

        $parametrs = $contstructor->getParameters();

        $dependencies = $this->resolveClassDependencies($parametrs);

        return $reflection->newInstanceArgs($dependencies);
    }

    private function resolveClassDependencies(array $parametrs): array
    {
        $dependencies = [];
        /** @var \ReflectionParameter $parametr */
        foreach ($parametrs as $parametr) {
            $dependencies[] = $this->get($parametr->getType()->getName());
        }
        return $dependencies;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
