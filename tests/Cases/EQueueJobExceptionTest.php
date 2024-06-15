<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestJob;
use Tests\Implementations\EQueueTestService;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueueJobExceptionTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueTestWorkerManager(
            iterationsCount: 3
        );

        $service = new EQueueTestService();

        $job = new EQueueTestJob(
            uuid: uniqid(),
            entityId: mt_rand(1000, 10000),
            isException: true,
        );

        $service->pushJob($job);

        $entityId = $job->getEntityId();
        $jobUuid  = $job->getUuid();

        $this->assertTrue(
            $service->hasEntity($entityId)
        );

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            service: $service
        );

        $worker->run(uniqid());

        $this->assertTrue(
            $service->wasJobError($jobUuid)
        );
    }
}
