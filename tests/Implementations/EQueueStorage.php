<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueJobInterface;
use EQueue\Contracts\EQueueStorageInterface;
use EQueue\Entities\EQueueJobsContainer;
use Throwable;

class EQueueStorage implements EQueueStorageInterface
{
    public function pushJob(EQueueJobInterface $job): void
    {
        // TODO
    }

    public function borrowEntityId(): ?string
    {
        return null;
    }

    public function getJobsByEntityId(string $entityId): EQueueJobsContainer
    {
        return new EQueueJobsContainer();
    }

    public function onJobHandlingError(EQueueJobInterface $job, Throwable $exception): void
    {
        // TODO
    }

    public function deleteJobsByEntityId(string $entityId): void
    {
        // TODO
    }

    public function restoreByEntityId(string $entityId): void
    {
        // TODO
    }

    public function releaseEntityId(string $entityId): void
    {
        // TODO
    }

    public function onJobHandled(EQueueJobInterface $job): void
    {
        // TODO
    }
}
