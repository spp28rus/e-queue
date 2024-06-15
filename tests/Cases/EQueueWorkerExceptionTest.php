<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestService;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueueWorkerExceptionTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueTestWorkerManager(
            iterationsCount: 3
        );

        $service = new EQueueTestService(
            isException: true
        );

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            service: $service
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
