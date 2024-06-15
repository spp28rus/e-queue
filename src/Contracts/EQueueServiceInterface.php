<?php

namespace EQueue\Contracts;

use EQueue\Entities\EQueueJobsContainer;
use Throwable;

interface EQueueServiceInterface
{
    public function pushJob(EQueueJobInterface $job): void;

    public function borrowEntityId(): ?int;

    public function findJobsByBorrowedEntityId(int $entityId): EQueueJobsContainer;

    public function onJobHandlingError(EQueueJobInterface $job, Throwable $exception): void;

    public function releaseEntityId(int $entityId): void;

    public function onJobHandled(EQueueJobInterface $job): void;
}
