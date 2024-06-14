<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestJob;
use Tests\Implementations\EQueueTestStorage;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueuePusherTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $storage = new EQueueTestStorage();

        $job = new EQueueTestJob(
            uuid: uniqid(),
            entityId: uniqid(),
            isException: false,
        );

        $storage->pushJob($job);

        $this->assertTrue(
            $storage->hasEntity($job->getEntityId())
        );
    }
}
