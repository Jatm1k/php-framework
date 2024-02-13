<?php

namespace App\Services;

use App\Entities\Post;
use Doctrine\DBAL\Connection;
use Jatmy\Framework\Dbal\EntityService;
use Jatmy\Framework\Http\Exceptions\NotFoundException;

class PostService
{
    public function __construct(
        private EntityService $service,
    ) {
        
    }
    public function save(Post $post): Post
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();
        $queryBuilder
            ->insert('posts')
            ->values([
                'title' => ':title',
                'body' => ':body',
                'created_at' => ':created_at',
            ])
            ->setParameters([
                'title' => $post->getTitle(),
                'body' => $post->getBody(),
                'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            ])
            ->executeQuery();
        $post->setId($this->service->save($post));
        return $post;
    }

    public function find(int $id): ?Post
    {
        $queryBuilder = $this->service->getConnection()->createQueryBuilder();
        $result = $queryBuilder
            ->select('*')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();
        
        $post = $result->fetchAssociative();
        if(!$post) {
            return null;
        }
        return Post::create(
            id: $post['id'],
            title: $post['title'],
            body: $post['body'],
            createdAt: new \DateTimeImmutable($post['created_at']),
        );
    }

    public function findOrFail(int $id): Post
    {
        $post = $this->find($id);

        if(is_null($post)) {
            throw new NotFoundException('Post not found');
        }

        return $post;
    }
}
