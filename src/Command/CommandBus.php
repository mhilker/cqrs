<?php

declare(strict_types=1);

namespace Commander\Command;

interface CommandBus
{
    public function execute(Command $command): void;
}
