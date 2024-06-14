<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseTestCase;
use Tests\Implementations\EQueueStorage;
use Tests\Implementations\EQueueWorkerManager;

class WorkerExceptionTest extends BaseTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueWorkerManager(
            iterationsCount: 3
        );

        $storage = new EQueueStorage(
            isException: true
        );

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            storage: $storage
        );

        $worker->run(uniqid());

        $this->assertTrue(
            $workerManager->wasStarted()
        );

        $this->assertTrue(
            $workerManager->wasError()
        );

        $this->assertTrue(
            $workerManager->wasStopped()
        );

        $this->assertEquals(
            2,
            $workerManager->getIterationsCount()
        );
    }
}
