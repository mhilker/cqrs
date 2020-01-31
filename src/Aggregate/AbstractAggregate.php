<?php

declare(strict_types=1);

namespace Commander\Aggregate;

use Commander\Event\Event;
use Commander\Event\Events;

abstract class AbstractAggregate
{
    private array $events = [];

    protected function __construct(?Events $events)
    {
        if ($events !== null) {
            foreach ($events as $event) {
                $this->apply($event);
            }
        }
    }

    public static function from(Events $events): self
    {
        return new static($events);
    }

    public function record(Event $event): void
    {
        $this->apply($event);
        $this->events[] = $event;
    }

    public function getEvents(): Events
    {
        return Events::from($this->events);
    }

    abstract protected function apply(Event $event): void;

    abstract public function getAggregateId(): AggregateId;
}
