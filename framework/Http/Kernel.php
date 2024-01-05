<?php

namespace Jatmy\Framework\Http;

use FastRoute\RouteCollector;
use Jatmy\Framework\Http\Exceptions\HttpException;
use Jatmy\Framework\Http\Exceptions\MethodNotAllowedException;
use Jatmy\Framework\Http\Exceptions\RouteNotFoundException;
use Jatmy\Framework\Routing\RouterInterface;

use function FastRoute\simpleDispatcher;

class Kernel
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (HttpException $e) {
            $response = new Response($e->getMessage(), $e->getStatusCode());
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 500);
        }
        return $response;
    }
}
