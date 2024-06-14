<?php

namespace Tests\Implementations;

use EQueue\Contracts\EQueueWorkerManagerInterface;
use Throwable;

class EQueueWorkerManager implements EQueueWorkerManagerInterface
{
    private bool $wasStarted = false;

    public function onStarted(string $uuid): void
    {
        $this->wasStarted = true;
    }

    public function stop(string $uuid): bool
    {
        return true;
    }

    public function onStopped(string $uuid): void
    {
        // TODO
    }

    public function onError(string $uuid, Throwable $exception): void
    {
        // TODO
    }

    public function wasStarted(): bool
    {
        return $this->wasStarted;
    }
}
