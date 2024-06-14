<?php

namespace EQueue;

use EQueue\Contracts\EQueueStorageInterface;
use EQueue\Contracts\EQueueWorkerManagerInterface;
use Throwable;

readonly class EQueueWorker
{
    public function __construct(
        private EQueueWorkerManagerInterface $workerManager,
        private EQueueStorageInterface $storage
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
        while (!$this->workerManager->stop($workerUuid)) {
            $entityId = $this->storage->borrowEntityId();

            if (is_null($entityId)) {
                continue;
            }

            $jobsContainer = $this->storage->getJobsByEntityId($entityId);

            foreach ($jobsContainer->get() as $job) {
                try {
                    $job->handle();
                } catch (Throwable $exception) {
                    $this->storage->onJobHandlingError($job, $exception);

                    $this->storage->deleteJobsByEntityId($entityId);

                    $this->storage->restoreByEntityId($entityId);

                    break;
                }

                $this->storage->onJobHandled($job);
            }

            $this->storage->releaseEntityId($entityId);
        }
    }
}
