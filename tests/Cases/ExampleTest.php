<?php

namespace Tests\Cases;

use EQueue\EQueueWorker;
use Tests\BaseTestCase;
use Tests\Implementations\EQueueStorage;
use Tests\Implementations\EQueueWorkerManager;

class ExampleTest extends BaseTestCase
{
    public function test(): void
    {
        $workerManager = new EQueueWorkerManager();
        $storage       = new EQueueStorage();

        $worker = new EQueueWorker(
            workerManager: $workerManager,
            storage: $storage
        );

        $worker->run(uniqid());

        $this->assertTrue(
            $workerManager->wasStarted()
        );
    }
}
