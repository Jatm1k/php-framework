<?php

namespace Jatmy\Framework\Http;

use Exception;
use Jatmy\Framework\Http\Events\ResponseEvent;
use League\Container\Container;
use Jatmy\Framework\Http\Exceptions\HttpException;
use Jatmy\Framework\Http\Middleware\RequestHandlerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private Container $container,
        private RequestHandlerInterface $requestHandler,
        private EventDispatcherInterface $eventDispatcher,
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
        
        $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));

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
