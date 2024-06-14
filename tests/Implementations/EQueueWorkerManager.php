<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueWorkerManagerInterface;
use Throwable;

class EQueueWorkerManager implements EQueueWorkerManagerInterface
{
    private bool $wasStarted = false;
    private bool $wasStopped = false;
    private bool $wasError = false;

    public function __construct(
        private int $iterationsCount
    ) {
    }

    public function onStarted(string $uuid): void
    {
        $this->wasStarted = true;
    }

    public function stop(string $uuid): bool
    {
        return --$this->iterationsCount === 0;
    }

    public function onStopped(string $uuid): void
    {
        $this->wasStopped = true;
    }

    public function onError(string $uuid, Throwable $exception): void
    {
        $this->wasError = true;
    }

    /**
     * Tests
     */

    public function wasStarted(): bool
    {
        return $this->wasStarted;
    }

    public function wasStopped(): bool
    {
        return $this->wasStopped;
    }

    public function wasError(): bool
    {
        return $this->wasError;
    }

    public function getIterationsCount(): int
    {
        return $this->iterationsCount;
    }
}
