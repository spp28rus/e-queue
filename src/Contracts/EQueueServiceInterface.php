<?php

namespace EQueue\Contracts;

use EQueue\Entities\EQueueJobsContainer;
use Throwable;

interface EQueueServiceInterface
{
    public function pushJob(EQueueJobInterface $job): void;

    public function borrow(): ?string;

    public function isActualBorrowingUuid(string $borrowingUuid): bool;

    public function findJobsByBorrowingUuid(string $borrowingUuid): EQueueJobsContainer;

    public function onJobHandlingError(EQueueJobInterface $job, Throwable $exception): void;

    public function releaseBorrowing(string $borrowingUuid): void;

    public function onJobHandled(EQueueJobInterface $job): void;
}
