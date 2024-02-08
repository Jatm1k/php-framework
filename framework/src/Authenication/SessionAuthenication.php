<?php

namespace Jatmy\Framework\Authenication;

use Jatmy\Framework\Session\Session;
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
        $this->session->set(Session::AUTH_KEY, $user->getId());

        $this->user = $user;
    }

    public function logout()
    {
        $this->session->remove(Session::AUTH_KEY);
        unset($this->user);
    }

    public function getUser(): ?AuthUserInterface
    {
        return $this->user;
    }

    public function check(): bool
    {
        return $this->session->has(Session::AUTH_KEY);
    }
}
