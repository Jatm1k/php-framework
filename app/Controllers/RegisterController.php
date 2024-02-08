<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Jatmy\Framework\Authenication\SessionAuthInterface;
use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\RedirectResponse;
use Jatmy\Framework\Http\Response;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private SessionAuthInterface $auth,
    ) {
    }
    public function index(): Response
    {
        return $this->render('register');
    }

    public function store()
    {
        $form = new RegisterForm($this->userService);

        $form->setFields(
            $this->request->input('name'),
            $this->request->input('email'),
            $this->request->input('password'),
            $this->request->input('password_confirmation')
        );
        if($form->hasValidationErrors()) {
            $this->request->getSession()->setFlash('errors', $form->getValidationErrors());

            return new RedirectResponse('/register');
        }

        $user = $form->save();

        $this->request->getSession()->setFlash('success', 'User created successfully');

        $this->auth->login($user);

        return new RedirectResponse('/');
    }
}
