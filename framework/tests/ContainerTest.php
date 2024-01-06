<?php

namespace Jatmy\Framework\Tests;

use PHPUnit\Framework\TestCase;
use Jatmy\Framework\Container\Container;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {
        $container = new Container();

        $container->add('jatmy', Jatmy::class);

        $this->assertInstanceOf(Jatmy::class, $container->get('jatmy'));
    }
}
