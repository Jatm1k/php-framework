<?php

namespace Jatmy\Framework\Http\Middleware;

use Jatmy\Framework\Authenication\SessionAuthInterface;
use Jatmy\Framework\Http\RedirectResponse;
use Jatmy\Framework\Http\Request;
use Jatmy\Framework\Http\Response;
use Jatmy\Framework\Session\SessionInterface;

class Guest implements MiddlewareInterface
{
    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface $session,
    ) {
    }
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();
        if($this->auth->check()) {
            $this->session->setFlash('error', 'You are already logged in');
            return new RedirectResponse('/');
        }
        return $handler->handle($request);
    }
}
