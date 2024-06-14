<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueJobInterface;
use EQueue\Contracts\EQueueStorageInterface;
use EQueue\Entities\EQueueJobsContainer;
use RuntimeException;
use Throwable;

class EQueueTestStorage implements EQueueStorageInterface
{
    /** @var array<string, EQueueJobInterface> */
    private array $jobs = [];

    /** @var array<string> */
    private array $borrowedEntityIds = [];
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

    public function borrowEntityId(): ?string
    {
        if ($this->isException) {
            throw new RuntimeException();
        }

        if (empty($this->jobs)) {
            return null;
        }

        $entityId = $this->jobs[array_keys($this->jobs)[0]]->getEntityId();

        $this->borrowedEntityIds[] = $entityId;

        return $entityId;
    }

    public function getJobsByEntityId(string $entityId): EQueueJobsContainer
    {
        $container = new EQueueJobsContainer();

        foreach ($this->jobs as $job) {
            if ($job->getEntityId() !== $entityId) {
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

    public function releaseEntityId(string $entityId): void
    {
        $this->borrowedEntityIds = array_filter(
            $this->borrowedEntityIds,
            fn(string $borrowedEntityId) => $borrowedEntityId !== $entityId
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

    public function wasEntityReleased(string $entityId): bool
    {
        return !in_array($entityId, $this->borrowedEntityIds);
    }

    public function hasEntity(string $entityId): bool
    {
        return array_key_exists($entityId, $this->jobs);
    }
}
