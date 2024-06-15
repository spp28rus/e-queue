<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueJobInterface;
use EQueue\Contracts\EQueueServiceInterface;
use EQueue\Entities\EQueueJobsContainer;
use RuntimeException;
use Throwable;

class EQueueTestService implements EQueueServiceInterface
{
    /** @var array<string, EQueueJobInterface> */
    private array $jobs = [];

    /** @var array<string> */
    private array $borrowedUuids = [];
    /** @var array<string> */
    private array $errorJobUuids = [];
    /** @var array<string> */
    private array $handledJobUuids = [];

    public function __construct(
        private readonly bool $isException = false
    ) {
    }

    public function pushJob(EQueueJobInterface $job): void
    {
        $this->jobs[$job->getEntityId()] = $job;
    }

    public function borrow(): ?string
    {
        if ($this->isException) {
            throw new RuntimeException();
        }

        if (empty($this->jobs)) {
            return null;
        }

        $borrowingUuid = $this->jobs[array_keys($this->jobs)[0]]->getEntityId();

        $this->borrowedUuids[] = $borrowingUuid;

        return $borrowingUuid;
    }

    public function isActualBorrowingUuid(string $borrowingUuid): bool
    {
        return in_array($borrowingUuid, $this->borrowedUuids);
    }

    public function findJobsByBorrowingUuid(string $borrowingUuid): EQueueJobsContainer
    {
        $container = new EQueueJobsContainer();

        foreach ($this->jobs as $job) {
            if ($job->getEntityId() !== $borrowingUuid) {
                continue;
            }

            $container->add($job);
        }

        return $container;
    }

    public function onJobHandlingError(EQueueJobInterface $job, Throwable $exception): void
    {
        $this->errorJobUuids[] = $job->getUuid();
    }

    public function releaseBorrowing(string $borrowingUuid): void
    {
        $this->borrowedUuids = array_filter(
            $this->borrowedUuids,
            fn(string $borrowedUuid) => $borrowedUuid !== $borrowingUuid
        );
    }

    public function onJobHandled(EQueueJobInterface $job): void
    {
        $this->handledJobUuids[] = $job->getUuid();
    }

    /**
     * Tests
     */

    public function wasJobError(string $jobUuid): bool
    {
        return in_array($jobUuid, $this->errorJobUuids);
    }

    public function wasJobHandled(string $jobUuid): bool
    {
        return in_array($jobUuid, $this->handledJobUuids);
    }

    public function wasReleased(string $borrowedUuid): bool
    {
        return !in_array($borrowedUuid, $this->borrowedUuids);
    }

    public function hasJobsByBorrowedUuid(string $borrowedUuid): bool
    {
        return array_key_exists($borrowedUuid, $this->jobs);
    }
}
