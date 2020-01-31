<?php

declare(strict_types=1);

namespace Commander\Event;

use Commander\UUID;

interface Event
{
    public function getId(): UUID;

    public function getTopic(): string;
}
