<?php

namespace Jatmy\Framework\Authenication;

interface UserServiceInterface
{
    public function findByEmail(string $email): ?AuthUserInterface;
}
