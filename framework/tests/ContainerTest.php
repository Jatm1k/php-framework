<?php

namespace Jatmy\Framework\Tests;

use PHPUnit\Framework\TestCase;
use Jatmy\Framework\Container\Container;
use Jatmy\Framework\Container\Exceptions\ContainerException;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {
        $container = new Container();
        $id = 'jatmy';
        $container->add($id, Jatmy::class);

        $this->assertInstanceOf(Jatmy::class, $container->get($id));
    }
    public function test_container_has_exception_ContainerException_if_add_wrong_service()
    {
        $container = new Container();
        $id = 'no-class';
        $this->expectException(ContainerException::class);
        $container->add($id);
    }
    public function test_has_method()
    {
        $container = new Container();
        $id = 'jatmy';
        $container->add($id, Jatmy::class);
        $this->assertTrue($container->has($id));
        $this->assertFalse($container->has('no-class'));
    }
    public function test_recursively_autowired()
    {
        $container = new Container();
        $id = Jatmy::class;
        $container->add($id);
        /* @var Jatmy $jatmy */
        $jatmy = $container->get($id);
        $this->assertInstanceOf(Food::class, $jatmy->getFood());
    }
}
