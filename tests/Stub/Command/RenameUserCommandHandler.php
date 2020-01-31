<?php

declare(strict_types=1);

namespace Commander\Stub\Command;

use Commander\Stub\Aggregate\Exception\UserNotFoundException;
use Commander\Stub\Aggregate\Exception\UserNotSavedException;
use Commander\Stub\Aggregate\UserRepository;

class RenameUserCommandHandler
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UserNotFoundException
     * @throws UserNotSavedException
     */
    public function __invoke(RenameUserCommand $command): void
    {
        $id = $command->getId();
        $name = $command->getName();

        $user = $this->repository->load($id);
        $user->rename($name);

        $this->repository->save($user);
    }
}
