<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestJob;
use Tests\Implementations\EQueueTestService;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueuePusherTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $service = new EQueueTestService();

        $job = new EQueueTestJob(
            uuid: uniqid(),
            entityId: uniqid(),
            isException: false,
        );

        $service->pushJob($job);

        $this->assertTrue(
            $service->hasEntity($job->getEntityId())
        );
    }
}
