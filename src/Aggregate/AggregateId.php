<?php

declare(strict_types=1);

namespace Commander\Aggregate;

interface AggregateId
{
    public function asString(): string;
}