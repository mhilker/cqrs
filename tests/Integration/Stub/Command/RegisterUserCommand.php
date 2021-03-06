<?php

declare(strict_types=1);

namespace Commander\Integration\Stub\Command;

use Commander\Command\Command;
use Commander\Integration\Stub\EventStream\UserId;
use Commander\Integration\Stub\EventStream\UserName;
use Commander\ID\HasIdentifier;

final class RegisterUserCommand implements Command
{
    use HasIdentifier;

    private UserId $userId;
    private UserName $name;

    public function __construct(UserId $userId, UserName $name)
    {
        $this->userId = $userId;
        $this->name = $name;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getName(): UserName
    {
        return $this->name;
    }
}
