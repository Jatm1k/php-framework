<?php

namespace Jatmy\Framework\Authenication;

interface SessionAuthInterface
{
    public function authenicate(string $email, string $password): bool;

    public function login(AuthUserInterface $user);

    public function logout();

    public function getUser(): ?AuthUserInterface;

    public function check(): bool;
}
