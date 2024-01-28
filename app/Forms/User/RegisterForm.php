<?php

namespace App\Forms\User;

use App\Entities\User;
use App\Services\UserService;

class RegisterForm
{
    private string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmation;

    public function __construct(
        private UserService $userService,
    ) {
    }

    public function setFields(string $name, string $email, string $password, string $passwordConfirmation): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function getValidationErrors(): array
    {
        $errors = [];
        
        if(empty($this->name)) {
            $errors['name'][] = 'Name is required';
        }
        if(empty($this->email)) {
            $errors['email'][] = 'Email is required';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Email is not valid';
        }
        if(empty($this->password)) {
            $errors['password'][] = 'Password is required';
        }
        if(strlen($this->password) < 8) {
            $errors['password'][] = 'Password must be at least 8 characters';
        }
        if(empty($this->passwordConfirmation)) {
            $errors['password_confirmation'][] = 'Password confirmation is required';
        }
        if($this->password !== $this->passwordConfirmation) {
            $errors['password_confirmation'][] = 'Password confirmation must match password';
        }
        return $errors;
    }

    public function hasValidationErrors(): bool
    {
        return !empty($this->getValidationErrors());
    }

    public function save(): User
    {
        $user = User::create(
            $this->name,
            $this->email,
            password_hash($this->password, PASSWORD_BCRYPT),
        );

        $user = $this->userService->save($user);

        return $user;
    }
}
