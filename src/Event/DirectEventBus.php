<?php

declare(strict_types=1);

namespace Commander\Event;

use SplQueue;

final class DirectEventBus implements EventDispatcher, EventPublisher
{
    private EventHandlers $handlers;

    private SplQueue $queue;

    public function __construct(EventHandlers $handlers)
    {
        $this->handlers = $handlers;
        $this->queue = new SplQueue();
    }

    public function publish(Events $events): void
    {
        $this->queue->enqueue($events);
    }

    public function dispatch(): void
    {
        foreach ($this->handlers as $eventHandler) {
            $events = $this->queue->dequeue();
            foreach ($events as $event) {
                $eventHandler->handle($event);
            }
        }
    }
}
