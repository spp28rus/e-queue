<?php

namespace EQueue\Contracts;

use Throwable;

interface EQueueWorkerManagerInterface
{
    public function onStarted(string $uuid): void;

    public function stop(string $uuid): bool;

    public function onStopped(string $uuid): void;

    public function onError(string $uuid, Throwable $exception): void;
}
