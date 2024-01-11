<?php

namespace Jatmy\Framework\Console;

interface CommandInterface
{
    public function execute(array $parametrs = []): int;
}
