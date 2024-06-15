<?php

namespace EQueue;

use EQueue\Contracts\EQueueJobInterface;
use EQueue\Contracts\EQueueServiceInterface;

readonly class EQueuePusher
{
    public function __construct(
        private EQueueServiceInterface $service,
    ) {
    }

    public function push(EQueueJobInterface $job): void
    {
        $this->service->pushJob($job);
    }
}
