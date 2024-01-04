<?php

namespace Jatmy\Framework\Http;

use FastRoute\RouteCollector;

use function FastRoute\simpleDispatcher;

class Kernel
{
    public function handle(Request $request): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $collector->get('/', function () {
                $content = 'test';
                return new Response($content);
            });

            $collector->get('/posts/{id}', function (array $vars) {
                $content = "Post id {$vars['id']}";
                return new Response($content);
            });
        });
        
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath()
        );
        
        [$status, $handler, $vars] = $routeInfo;

        return $handler($vars);
    }
}
