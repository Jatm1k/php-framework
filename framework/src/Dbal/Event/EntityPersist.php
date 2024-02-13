<?php
namespace Jatmy\Framework\Dbal\Event;

use Jatmy\Framework\Dbal\Entity;
use Jatmy\Framework\Event\Event;

class EntityPersist extends Event
{
    public function __construct(private Entity $entity)
    {
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }
}
