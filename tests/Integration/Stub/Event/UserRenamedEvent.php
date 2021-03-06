<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\Event;

use Commander\Event\Event;
use Commander\Integration\Stub\EventStream\UserId;
use Commander\Integration\Stub\EventStream\UserName;

final class UserRenamedEvent implements Event
{
    public const TOPIC = 'com.example.event.user_renamed';
    public const VERSION = 1;

    private UserId $id;
    private UserName $name;

    private function __construct(UserId $id, UserName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public static function occur(UserId $id, UserName $name): self
    {
        return new self($id, $name);
    }

    public static function fromPayload(array $payload): Event
    {
        $id = UserId::fromV4($payload['id']);
        $name = UserName::from($payload['name']);

        return new self($id, $name);
    }

    public function getPayload(): array
    {
        return [
            'id'   => $this->id->asString(),
            'name' => $this->name->asString(),
        ];
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getTopic(): string
    {
        return self::TOPIC;
    }

    public function getVersion(): int
    {
        return self::VERSION;
    }
}
