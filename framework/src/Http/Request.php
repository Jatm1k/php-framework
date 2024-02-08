<?php

namespace Jatmy\Framework\Http;

use Jatmy\Framework\Session\SessionInterface;

class Request
{
    private SessionInterface $session;
    private mixed $routeHandler;
    private array $routeArgs;
    public function __construct(
        private readonly array $getParams,
        private readonly array $postData,
        private readonly array $cookies,
        private readonly array $files,
        private readonly array $server,
    ) {
        
    }
    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getPath(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function all(): array
    {
        return array_merge($this->getParams, $this->postData, $this->cookies, $this->files);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->all()[$key] ?? $default;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function setRouteHandler(mixed $handler): void
    {
        $this->routeHandler = $handler;
    }

    public function getRouteHandler(): mixed
    {
        return $this->routeHandler;
    }

    public function setRouteArgs(array $args): void
    {
        $this->routeArgs = $args;
    }

    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }
}
