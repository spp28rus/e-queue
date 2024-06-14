<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestJob;
use Tests\Implementations\EQueueTestStorage;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueueJobExceptionTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueTestWorkerManager(
            iterationsCount: 3
        );

        $storage = new EQueueTestStorage();

        $job = new EQueueTestJob(
            uuid: uniqid(),
            entityId: uniqid(),
            isException: true,
        );

        $storage->pushJob($job);

        $entityId = $job->getEntityId();
        $jobUuid  = $job->getUuid();

        $this->assertTrue(
            $storage->hasEntity($entityId)
        );

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            storage: $storage
        );

        $worker->run(uniqid());

        $this->assertTrue(
            $storage->wasJobError($jobUuid)
        );
    }
}
