<?php

namespace App\Services;

use App\Entities\User;
use Doctrine\DBAL\Connection;
use Jatmy\Framework\Authenication\AuthUserInterface;
use Jatmy\Framework\Authenication\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private Connection $connection,
    ) {
        
    }

    public function findByEmail(string $email): ?AuthUserInterface
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->executeQuery();
        
        $user = $result->fetchAssociative();
        if(!$user) {
            return null;
        }
        return User::create(
            id: $user['id'],
            name: $user['name'],
            email: $user['email'],
            password: $user['password'],
            createdAt: new \DateTimeImmutable($user['created_at']),
        );
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
