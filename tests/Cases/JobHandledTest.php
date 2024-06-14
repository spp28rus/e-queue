<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseTestCase;
use Tests\Implementations\EQueueJob;
use Tests\Implementations\EQueueStorage;
use Tests\Implementations\EQueueWorkerManager;

class JobHandledTest extends BaseTestCase
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
            isException: false,
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

        $this->assertFalse(
            $storage->wasJobError($jobUuid)
        );

        $this->assertFalse(
            $storage->wasEntityRestored($entityId)
        );

        $this->assertTrue(
            $storage->wasEntityReleased($entityId)
        );

        $this->assertTrue(
            $storage->wasJobHandled($jobUuid)
        );
    }
}
