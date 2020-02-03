<?php

declare(strict_types=1);

namespace Commander\Stub\Event;

use Commander\Event\Event;
use Commander\Event\EventHandler;
use Commander\Stub\Aggregate\UserId;
use Commander\Stub\Aggregate\UserName;
use Commander\Stub\Aggregate\UserRepository;

final class DisableUsersWithBlacklistedNamesPolicy implements EventHandler
{
    private const BLACKLISTED_NAMES = [
        'Test',
    ];

    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Event $event): void
    {
        switch ($event->getTopic()) {
            case UserRegisteredEvent::TOPIC:
                /** @var UserRegisteredEvent $event */
                $this->checkRegisteredUserName($event);
                break;
            case UserRenamedEvent::TOPIC:
                /** @var UserRenamedEvent $event */
                $this->checkRenamedUserName($event);
                break;
        }
    }

    private function checkRegisteredUserName(UserRegisteredEvent $event): void
    {
        $this->checkUserName($event->getId(), $event->getName());
    }

    private function checkRenamedUserName(UserRenamedEvent $event): void
    {
        $this->checkUserName($event->getId(), $event->getName());
    }

    private function checkUserName(UserId $id, UserName $name): void
    {
        if (in_array($name->asString(), self::BLACKLISTED_NAMES, false) === false) {
            return;
        }

        $user = $this->repository->load($id);
        $user->disable();
        $this->repository->save($user);
    }
}