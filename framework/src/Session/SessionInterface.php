<?php

namespace Jatmy\Framework\Session;

interface SessionInterface
{
    public function start(): void;
    public function get(string $key, $default = null);

    public function set(string $key, $value);

    public function has(string $key): bool;

    public function remove(string $key): void;

    public function getFlash(string $type): array;

    public function setFlash(string $type, string|array $message): void;

    public function hasFlash(string $type): bool;

    public function clearFlash(): void;
}
