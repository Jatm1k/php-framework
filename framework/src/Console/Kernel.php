<?php

namespace Jatmy\Framework\Console;

use DirectoryIterator;
use Psr\Container\ContainerInterface;
use ReflectionClass;

class Kernel
{

    public function __construct(
        private ContainerInterface $container,
        private Application $application,
    ) {
    }
    public function handle(): int
    {
        $this->registerCommands();
        return $this->application->run();
    }

    private function registerCommands(): void
    {
        $commandFiles = new DirectoryIterator(__DIR__ . '/Commands');
        $namespace = $this->container->get('framework-commands-namespace');
        foreach($commandFiles as $commandFile) {
            if(!$commandFile->isFile()) {
                continue;
            }
            $command = $namespace . pathinfo($commandFile, PATHINFO_FILENAME);

            if(is_subclass_of($command, CommandInterface::class)) {
                $name = (new ReflectionClass($command))->getProperty('name')->getDefaultValue();
                $this->container->add("console:$name", $command);
            }
        }
    }
}
