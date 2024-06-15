<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestJob;
use Tests\Implementations\EQueueTestService;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueueJobHandledTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueTestWorkerManager(
            iterationsCount: 3
        );

        $service = new EQueueTestService();

        $job = new EQueueTestJob(
            uuid: uniqid(),
            entityId: uniqid(),
            isException: false,
        );

        $service->pushJob($job);

        $jobUuid      = $job->getUuid();
        $borrowedUuid = $job->getEntityId();

        $this->assertTrue(
            $service->hasJobsByBorrowedUuid($borrowedUuid)
        );

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            service: $service
        );

        $worker->run(uniqid());

        $this->assertFalse(
            $service->wasJobError($jobUuid)
        );

        $this->assertTrue(
            $service->wasReleased($borrowedUuid)
        );

        $this->assertTrue(
            $service->wasJobHandled($jobUuid)
        );
    }
}
