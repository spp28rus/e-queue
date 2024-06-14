<?php

namespace EQueue\Entities;

use EQueue\Contracts\EQueueJobInterface;

class EQueueJobsContainer
{
    private int $count = 0;
    private array $jobs = [];

    public function add(EQueueJobInterface $job): static
    {
        $this->jobs[] = $job;

        ++$this->count;

        return $this;
    }

    /**
     * @return EQueueJobInterface[]
     */
    public function get(): array
    {
        return $this->jobs;
    }

    public function count(): int
    {
        return $this->count;
    }
}
