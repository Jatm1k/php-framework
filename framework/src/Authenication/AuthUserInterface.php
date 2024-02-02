<?php

namespace Jatmy\Framework\Authenication;

interface AuthUserInterface
{
    public function getId(): ?int;

    public function getPassword(): string;

    public function getEmail(): string;
}
