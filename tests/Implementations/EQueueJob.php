<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueJobInterface;
use RuntimeException;

readonly class EQueueJob implements EQueueJobInterface
{
    public function __construct(
        private string $uuid,
        private string $entityId,
        private bool $isException,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEntityId(): string
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
