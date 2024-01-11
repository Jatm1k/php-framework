<?php

namespace Jatmy\Framework\Console\Commands;

use Jatmy\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';
    
    public function execute(array $parametrs = []): int
    {
        return 0;
    }
}
