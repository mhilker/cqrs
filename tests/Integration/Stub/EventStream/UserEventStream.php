<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\EventStream;

use Commander\EventStream\AbstractEventStream;
use Commander\Event\Event;
use Commander\Integration\Stub\Event\UserDisabledEvent;
use Commander\Integration\Stub\Event\UserRegisteredEvent;
use Commander\Integration\Stub\Event\UserRenamedEvent;
use Commander\ID\Identifier;

final class UserEventStream extends AbstractEventStream
{
    private UserId $id;
    private UserName $name;
    private bool $active;

    public static function register(UserId $id, UserName $name): UserEventStream
    {
        $user = new self(null);
        $user->record(UserRegisteredEvent::occur($id, $name));
        return $user;
    }

    private function applyUserRegistered(UserRegisteredEvent $event): void
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->active = true;
    }

    public function rename(UserName $newName): void
    {
        if ($this->name->notEqual($newName)) {
            $this->record(UserRenamedEvent::occur($this->id, $newName));
        }
    }

    private function applyUserRenamed(UserRenamedEvent $event): void
    {
        $this->name = $event->getName();
    }

    public function disable(): void
    {
        if ($this->active) {
            $this->record(UserDisabledEvent::occur($this->id));
        }
    }

    private function applyUserDisabled(UserDisabledEvent $event): void
    {
        $this->active = false;
    }

    protected function dispatch(Event $event): void
    {
        switch ($event->getTopic()) {
            case UserRegisteredEvent::TOPIC:
                /** @var UserRegisteredEvent $event */
                $this->applyUserRegistered($event);
                break;
            case UserRenamedEvent::TOPIC:
                /** @var UserRenamedEvent $event */
                $this->applyUserRenamed($event);
                break;
            case UserDisabledEvent::TOPIC:
                /** @var UserDisabledEvent $event */
                $this->applyUserDisabled($event);
                break;
        }
    }

    public function getEventStreamId(): Identifier
    {
        return $this->id;
    }
}
