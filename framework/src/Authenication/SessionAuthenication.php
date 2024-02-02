<?php

namespace Jatmy\Framework\Authenication;

use Jatmy\Framework\Session\SessionInterface;

class SessionAuthenication implements SessionAuthInterface
{
    private AuthUserInterface $user;

    public function __construct(
        private UserServiceInterface $userService,
        private SessionInterface $session,
    ) {
    }

    public function authenicate(string $email, string $password): bool
    {
        $user = $this->userService->findByEmail($email);

        if(!$user) {
            return false;
        }

        if(password_verify($password, $user->getPassword())) {
            $this->login($user);

            return true;
        }
        return false;
    }

    public function login(AuthUserInterface $user): void
    {
        $this->session->set('user_id', $user->getId());

        $this->user = $user;
    }

    public function logout()
    {
        $this->session->remove('user_id');
        unset($this->user);
    }

    public function getUser(): ?AuthUserInterface
    {
        return $this->user;
    }
}
