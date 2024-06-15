<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueJobInterface;
use RuntimeException;

readonly class EQueueTestJob implements EQueueJobInterface
{
    public function __construct(
        private string $uuid,
        private int $entityId,
        private bool $isException,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEntityId(): int
    {
        return $this->entityId;
    }

    public function handle(): void
    {
        if ($this->isException) {
            throw new RuntimeException($this->entityId);
        }
    }
}
