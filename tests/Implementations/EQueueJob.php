<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueJobInterface;

class EQueueJob implements EQueueJobInterface
{
    public function getUuid(): string
    {
        return uniqid();
    }

    public function getEntityId(): string
    {
        return uniqid();
    }

    public function handle(): void
    {
        // TODO
    }
}
