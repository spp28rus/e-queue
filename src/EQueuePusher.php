<?php

namespace EQueue;

use EQueue\Contracts\EQueueJobInterface;
use EQueue\Contracts\EQueueStorageInterface;

readonly class EQueuePusher
{
    public function __construct(
        private EQueueStorageInterface $repository,
    ) {
    }

    public function push(EQueueJobInterface $job): void
    {
        $this->repository->pushJob($job);
    }
}
