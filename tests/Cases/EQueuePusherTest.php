<?php

namespace Tests\Cases;

use EQueue\EQueuePusher;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestJob;
use Tests\Implementations\EQueueTestService;

class EQueuePusherTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $service = new EQueueTestService();

        $pusher = new EQueuePusher($service);

        $job = new EQueueTestJob(
            uuid: uniqid(),
            entityId: mt_rand(1000, 10000),
            isException: false,
        );

        $pusher->push($job);

        $this->assertTrue(
            $service->hasEntity($job->getEntityId())
        );
    }
}
