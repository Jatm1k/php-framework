<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Jatmy\Framework\Authenication\SessionAuthInterface;
use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\RedirectResponse;
use Jatmy\Framework\Http\Response;

class LoginController extends AbstractController
{
    public function __construct(
        private SessionAuthInterface $sessionAuth,
    ) {
    }
    public function index(): Response
    {
        return $this->render('login');
    }

    public function store()
    {
        $isAuth = $this->sessionAuth->authenicate(
            $this->request->input('email'),
            $this->request->input('password')
        );

        if(!$isAuth) {
            $this->request->getSession()->setFlash('error', 'Invalid email or password');

            return new RedirectResponse('/login');
        }

        $this->request->getSession()->setFlash('success', 'User logged in successfully');
        return new RedirectResponse('/');
    }

    public function destroy(): Response
    {
        $this->sessionAuth->logout();
        $this->request->getSession()->setFlash('success', 'User logged out successfully');
        return new RedirectResponse('/');
    }
}
