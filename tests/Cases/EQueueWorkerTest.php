<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseEQueueTestCase;
use Tests\Implementations\EQueueTestStorage;
use Tests\Implementations\EQueueTestWorkerManager;

class EQueueWorkerTest extends BaseEQueueTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueTestWorkerManager(
            iterationsCount: 3
        );

        $storage = new EQueueTestStorage();

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            storage: $storage
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
