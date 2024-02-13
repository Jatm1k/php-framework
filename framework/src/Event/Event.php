<?php
namespace Jatmy\Framework\Event;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class Event implements StoppableEventInterface
{
    private bool $propagetionStopped = false;

    public function isPropagationStopped(): bool
    {
        return $this->propagetionStopped;
    }

    public function stopPropagation(): void
    {
        $this->propagetionStopped = true;
    }
}
