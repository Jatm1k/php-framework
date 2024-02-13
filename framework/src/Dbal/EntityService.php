<?php
namespace Jatmy\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Jatmy\Framework\Dbal\Event\EntityPersist;
use Psr\EventDispatcher\EventDispatcherInterface;

class EntityService
{
    public function __construct(
        private Connection $connection,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function save(Entity $entity): int
    {
        $entityId = $this->connection->lastInsertId();
        $entity->setId($entityId);
        $this->eventDispatcher->dispatch(new EntityPersist($entity));
        return $entityId;
    }
}
