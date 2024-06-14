<?php

namespace EQueue\Contracts;

use EQueue\Entities\EQueueJobsContainer;
use Throwable;

interface EQueueStorageInterface
{
    public function pushJob(EQueueJobInterface $job): void;

    public function borrowEntityId(): ?string;

    public function findJobsByBorrowedEntityId(string $entityId): EQueueJobsContainer;

    public function onJobHandlingError(EQueueJobInterface $job, Throwable $exception): void;

    public function releaseEntityId(string $entityId): void;

    public function onJobHandled(EQueueJobInterface $job): void;
}
