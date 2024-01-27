<?php

namespace App\Entities;

use DateTimeImmutable;

class User
{

    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private string $password,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public static function create(
        string $name,
        string $email,
        string $password,
        DateTimeImmutable $createdAt = null,
        int $id = null,
    ): static {
        return new static($id, $name, $email, $password, $createdAt ?? new DateTimeImmutable());
    }
}
