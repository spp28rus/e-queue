<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestService;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueueWorkerTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueTestWorkerManager(
            iterationsCount: 3
        );

        $service = new EQueueTestService();

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            service: $service
        );

        $worker->run(uniqid());

        $this->assertTrue(
            $workerManager->wasStarted()
        );

        $this->assertTrue(
            $workerManager->wasStopped()
        );

        $this->assertEquals(
            0,
            $workerManager->getIterationsCount()
        );
    }
}
