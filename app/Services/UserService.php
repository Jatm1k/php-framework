<?php

namespace App\Services;

use App\Entities\User;
use Doctrine\DBAL\Connection;
use Jatmy\Framework\Http\Exceptions\NotFoundException;

class UserService
{
    public function __construct(
        private Connection $connection,
    ) {
        
    }
    public function save(User $user): User
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('users')
            ->values([
                'name' => ':name',
                'email' => ':email',
                'password' => ':password',
                'created_at' => ':created_at',
            ])
            ->setParameters([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'created_at' => $user->getCreatedAt()->format('Y-m-d H:i:s'),
            ])
            ->executeQuery();
        $user->setId($this->connection->lastInsertId());
        return $user;
    }
}
