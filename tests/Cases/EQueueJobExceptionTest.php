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
            entityId: uniqid(),
            isException: true,
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

        $this->assertTrue(
            $service->wasJobError($jobUuid)
        );
    }
}
