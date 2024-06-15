<?php

namespace EQueue;

use EQueue\Contracts\EQueueServiceInterface;
use EQueue\Contracts\EQueueWorkerManagerInterface;
use Throwable;

readonly class EQueueWorker
{
    public function __construct(
        private EQueueWorkerManagerInterface $workerManager,
        private EQueueServiceInterface $service
    ) {
    }

    public function run(string $workerUuid): void
    {
        $this->workerManager->onStarted($workerUuid);

        try {
            $this->onRun($workerUuid);
        } catch (Throwable $exception) {
            $this->workerManager->onError($workerUuid, $exception);
        }

        $this->workerManager->onStopped($workerUuid);
    }

    private function onRun(string $workerUuid): void
    {
        while (!$this->workerManager->while($workerUuid)) {
            $entityId = $this->service->borrowEntityId();

            if (is_null($entityId)) {
                continue;
            }

            $jobsContainer = $this->service->findJobsByBorrowedEntityId($entityId);

            foreach ($jobsContainer->get() as $job) {
                try {
                    $job->handle();
                } catch (Throwable $exception) {
                    $this->service->onJobHandlingError($job, $exception);

                    break;
                }

                $this->service->onJobHandled($job);
            }

            $this->service->releaseEntityId($entityId);
        }
    }
}
