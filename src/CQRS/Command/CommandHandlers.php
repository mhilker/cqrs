<?php

declare(strict_types=1);

namespace MHilker\CQRS\Command;

use MHilker\CQRS\Command\Exception\CommandHandlerNotFoundException;
use MHilker\CQRS\Command\Exception\InvalidCommandClassException;

class CommandHandlers
{
    private $handlers = [];

    /**
     * @param callable $commandHandler
     * @param string $commandClass
     * @return void
     */
    public function addHandler(callable $commandHandler, string $commandClass): void
    {
        if (class_exists($commandClass) === false) {
            throw new InvalidCommandClassException();
        }

        $this->handlers[$commandClass] = $commandHandler;
    }

    /**
     * @param string $commandClass
     * @return bool
     */
    public function hasHandlerForCommand(string $commandClass): bool
    {
        return isset($this->handlers[$commandClass]) === true;
    }

    /**
     * @param string $commandClass
     * @return callable
     * @throws CommandHandlerNotFoundException
     */
    public function getHandlerForCommand(string $commandClass): callable
    {
        if ($this->hasHandlerForCommand($commandClass) === false) {
            throw new CommandHandlerNotFoundException();
        }

        return $this->handlers[$commandClass];
    }
}
