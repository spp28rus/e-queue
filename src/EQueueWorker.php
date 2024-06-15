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
        while (!$this->workerManager->stop($workerUuid)) {
            $borrowingUuid = $this->service->borrow();

            if (is_null($borrowingUuid)) {
                continue;
            }

            $jobsContainer = $this->service->findJobsByBorrowingUuid($borrowingUuid);

            foreach ($jobsContainer->get() as $job) {
                if (!$this->service->isActualBorrowingUuid($borrowingUuid)) {
                    break;
                }

                try {
                    $job->handle();
                } catch (Throwable $exception) {
                    $this->service->onJobHandlingError($job, $exception);

                    break;
                }

                $this->service->onJobHandled($job);
            }

            $this->service->releaseBorrowing($borrowingUuid);
        }
    }
}
