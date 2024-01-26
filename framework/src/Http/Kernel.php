<?php

namespace Jatmy\Framework\Http;

use Exception;
use Doctrine\DBAL\Connection;
use League\Container\Container;
use Jatmy\Framework\Routing\RouterInterface;
use Jatmy\Framework\Http\Exceptions\HttpException;
use Jatmy\Framework\Http\Middleware\RequestHandlerInterface;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private RouterInterface $router,
        private Container $container,
        private RequestHandlerInterface $requestHandler,
    ) {
        $this->appEnv = $container->get('APP_ENV');
    }
    public function handle(Request $request): Response
    {
        try {
            $response = $this->requestHandler->handle($request);
        } catch (\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }
        return $response;
    }

    private function createExceptionResponse(Exception $e): Response
    {
        if(in_array($this->appEnv, ['dev', 'local', 'testing'])) {
            throw $e;
        }
        if($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getStatusCode());
        }
        return new Response('Server error', 500);
    }

    public function terminate(Request $request, Response $response): void
    {
        $request->getSession()?->clearFlash();
    }
}
