<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseTestCase;
use Tests\Implementations\EQueueJob;
use Tests\Implementations\EQueueStorage;
use Tests\Implementations\EQueueWorkerManager;

class JobExceptionTest extends BaseTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueWorkerManager(
            iterationsCount: 3
        );

        $storage = new EQueueStorage();

        $job = new EQueueJob(
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

        $this->assertTrue(
            $storage->wasEntityRestored($entityId)
        );

        $this->assertFalse(
            $storage->hasEntity($entityId)
        );
    }
}
